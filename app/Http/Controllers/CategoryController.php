<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller {

    public function __construct() {
        $this->middleware('auth:admin-api')->except(['index', 'show', 'products']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $categories = Category::query();
        if ($request->has('withoutChildren') && $request->get('withoutChildren') == '1') {
            $categories->whereDoesntHave('children');
        }

        if ($request->has('root') && $request->get('root') == 1) {
            $categories->whereDoesntHave('parent');
        }

        if ($request->has('orderByChildren')) {
            $categories->withCount('children')->orderBy('children_count', 'desc');
        }

        $categories->with('parent');

        if ($request->has('withChildren')) {
            $categories->with('children');
        }

        if ($request->has('hideChildren')) {
            $categories->without(['children']);
        }
        return $categories->get();
    }

    public function activeIndex(Request $request) {
        $categories = Category::query()->where('status', 1);
        if ($request->has('withoutChildren') && $request->get('withoutChildren') == '1') {
            $categories->whereDoesntHave('children');
        }

        if ($request->has('root') && $request->get('root') == 1) {
            $categories->whereDoesntHave('parent');
        }

        if ($request->has('orderByChildren')) {
            $categories->withCount('children')->orderBy('children_count', 'desc');
        }

        return $categories->with('parent')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validated = $this->validate($request, [
            'name' => 'required|string',
            'name_en' => 'required|string',
            'cover' => 'sometimes|file',
        ]);
        if ($request->has('parent_id') && $request->parent_id != 'null') {
            $this->validate($request, [
                'parent_id' => 'required|numeric|exists:App\Models\Category,id'
            ]);
            $validated['parent_id'] = $request->parent_id;
        }

        if ($request->has('status')) {
            $validated['status'] = ($request->status == 'true' || $request->status == '1');
        }

        $cover = null;
        if ($request->hasFile('cover')) {
            $cover = Storage::disk('public')->put('category/cover', $request->file('cover'));
        }

        Category::create([
            'name' => $validated['name'],
            'name_en' => $validated['name_en'],
            'slug' => \Str::slug($validated['name_en']),
            'cover' => $cover,
            'status' => $validated['status'],
            'parent_id' => $validated['parent_id'] ?? null
        ])->load(['parent', 'children']);

        return $this->index($request);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Category $category) {
        $category->load('parent');

        if ($request->has('withChildren')) {
            $category->load('children');
        }

        $category->loadCount('products');
        return $category;
    }

    public function products(Request $request, Category $category) {
        if (!$category->status) {
            return response()->json([
                'ok' => false,
                'message' => 'شما مجاز به مشاهده این اطلاعات نیستید'
            ])->setStatusCode(403);
        }
        $products = $category->products()->where('status', 1);

        if ($request->has('paginate')) {
            return $products->paginate(20);
        }

        return $products->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category) {
        $this->validate($request, [
            'name' => 'required|string',
            'name_en' => 'required|string',
        ]);

        if ($request->hasFile('cover')) {
            $this->validate($request, [
                'cover' => 'required|file|mimes:jpg,png,jpeg'
            ]);
            $category->cover = Storage::disk('public')->put('category/cover', $request->file('cover'));
        }

        if ($request->has('status')) {
            $category->status = ($request->status == '1' || $request->status == 'true');
        }

        if ($request->has('parent_id') && $request->parent_id != 'null' && $request->parent_id != $category->id) {
            $this->validate($request, [
                'parent_id' => 'required|numeric|exists:App\Models\Category,id'
            ]);
            $category->parent_id = $request->parent_id;
        }

        $category->name = $request->name;
        $category->name_en = $request->name_en;
        $category->slug = \Str::slug($request->name_en);


        $category->save();
        $category->load(['parent', 'children']);
        $category->loadCount('products');
        return $category;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Category $category) {
        $category->deleteOrFail();
        return $this->index($request);
    }

    public function attachToProduct(Request $request, Product $product) {

        $this->validate($request, [
            'ids.*' => 'required|numeric|exists:App\Models\Category,id'
        ]);

        $product->categories()->detach();
        $product->categories()->attach($request->get('ids'));


        return $product->load('categories');
    }
}
