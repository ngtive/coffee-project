<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProductController extends Controller {

    public function __construct() {
//        $this->middleware(['auth:admin-api'])->except(['index', 'show']);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $productQuery = Product::query();

        if ($request->has('search')) {
            $validated = $this->validate($request, [
                'search' => 'required|string|max:100'
            ]);

            $productQuery->where(function ($productQuery) use ($validated) {
                $productQuery->where('title', 'like', '%' . $validated['search'] . '%')
                    ->orWhere('title_en', 'like', '%' . $validated['search'] . '%');
            });
        }

        if ($request->has('withCategories')) {
            $productQuery->with(['categories']);
        }

        if ($request->has('hasDiscount')) {
            $productQuery->whereHas('discount');
        }

        if ($request->has('orderByDiscount')) {
            $productQuery->orderBy('discount.discount');
        }


        if ($request->has('sort')) {
            $this->validate($request, [
                'sort' => 'required|string|max:20'
            ]);
            $sort = $request->get('sort');

            switch ($sort) {
                case 'newest':
                    $productQuery->orderBy('created_at', 'desc');
                    break;
                case 'most-wanted';
                    break;
                default:
                    break;
            }
        }

        if ($request->has('paginate')) {
            return $productQuery->paginate($request->get('per_page') ?? 20);
        }

        return $productQuery->get();
    }

    public function activeIndex(Request $request) {
        $productQuery = Product::query()->where('status', 1);

        if ($request->has('search')) {
            $validated = $this->validate($request, [
                'search' => 'required|string|max:100'
            ]);

            $productQuery->where(function ($productQuery) use ($validated) {
                $productQuery->where('title', 'like', '%' . $validated['search'] . '%')
                    ->orWhere('title_en', 'like', '%' . $validated['search'] . '%');
            });
        }

        if ($request->has('withCategories')) {
            $productQuery->with(['categories']);
        }

        if ($request->has('hasDiscount')) {
            $productQuery->whereHas('discount');
        }


        if ($request->has('sort')) {
            $this->validate($request, [
                'sort' => 'required|string|max:20'
            ]);
            $sort = $request->get('sort');

            switch ($sort) {
                case 'newest':
                    $productQuery->orderBy('created_at', 'desc');
                    break;
                case 'most-wanted';
                    break;
                default:
                    break;
            }
        }

        if ($request->has('paginate')) {
            return $productQuery->paginate($request->get('per_page') ?? 20);
        }

        return $productQuery->get();
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
            'title' => 'required|string|max:200',
            'title_en' => 'required|string|max:200',
            'weight' => 'required|numeric',
            'cover' => 'required|file',
            'status' => 'required|string',
            'amount' => 'sometimes|nullable|numeric',
            'quantity' => 'sometimes|nullable|numeric',
            'description' => 'sometimes|nullable|string',
        ]);

        $validated['status'] = ($validated['status'] == '1' || $validated['status'] == 'true');


        $coverFile = $request->file('cover');
        $coverHash = \Str::random(18);

        $thumbs = [
            '100' => Image::make($request->file('cover'))->resize(100, 100),
            '200' => Image::make($request->file('cover'))->resize(200, 200),
            '300' => Image::make($request->file('cover'))->resize(300, 300),
            '400' => Image::make($request->file('cover'))->resize(400, 400),
            '500' => Image::make($request->file('cover'))->resize(500, 500)
        ];
        foreach ($thumbs as $key => $thumb) {
            Storage::disk('public')->put(
                'products/' . $coverHash . '/' . $key . '.' . $coverFile->guessExtension(),
                (string)$thumb->encode()
            );
        }

        $coverPath = Storage::disk('public')->putFileAs('products/' . $coverHash, $coverFile, 'original.' . $coverFile->guessExtension());

        $product = new Product();

        $product->title = $validated['title'];
        $product->title_en = $validated['title_en'];
        $product->weight = $validated['weight'];
        $product->cover = $coverPath;
        $product->amount = $validated['amount'] ?? null;
        $product->quantity = $validated['quantity'] ?? null;
        $product->slug = \Str::slug($validated['title_en']);
        $product->status = $validated['status'];

        return ['ok' => $product->save()];

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Product $product) {
        $product->load(['productAttributes.attributeValues.attribute']);

        if ($request->has('withCategories')) {
            $product->load(['categories'])->only('categories.id');
        }

        return $product;
    }

    public function showProduct(Request $request, Product $product) {
        if (!$product->status) {
            return response()->json([
                'ok' => false,
                'message' => 'شما مجاز به مشاهده این اطلاعات نیستید',
            ])->setStatusCode(403);
        }
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
    public function update(Request $request, Product $product) {
        $validated = $this->validate($request, [
            'title' => 'required|string',
            'title_en' => 'required|string',
            'weight' => 'required|numeric',
            'status' => 'required|string',
            'amount' => 'required|numeric',
            'quantity' => 'required|numeric',
            'description' => 'sometimes|nullable|string'
        ]);
        $product->title = $validated['title'];
        $product->title_en = $validated['title_en'];
        $product->weight = $validated['weight'];
        $product->slug = \Str::slug($validated['title_en']);
        $product->status = ($validated['status'] == '1' || $validated['status'] == 'true');
        $product->amount = $validated['amount'];
        $product->quantity = $validated['quantity'];
        $product->description = $validated['description'] ?? null;


        if ($request->hasFile('cover')) {
            $coverFile = $request->file('cover');
            $coverHash = \Str::random(18);
            $thumbs = [
                '100' => Image::make($request->file('cover'))->resize(100, 100),
                '200' => Image::make($request->file('cover'))->resize(200, 200),
                '300' => Image::make($request->file('cover'))->resize(300, 300),
                '400' => Image::make($request->file('cover'))->resize(400, 400),
                '500' => Image::make($request->file('cover'))->resize(500, 500)
            ];
            foreach ($thumbs as $key => $thumb) {
                Storage::disk('public')->put(
                    'products/' . $coverHash . '/' . $key . '.' . $coverFile->guessExtension(),
                    (string)$thumb->encode()
                );
            }
            $coverPath = Storage::disk('public')->putFileAs('products/' . $coverHash, $coverFile, 'original.' . $coverFile->guessExtension());
            $product->cover = $coverPath;
        }

        if ($request->has('brand_id')) {
            $this->validate($request, [
                'brand_id' => 'required|numeric|exists:App\Models\Brand,id',
            ]);
            $product->brand_id = $request->get('brand_id');
        }

        $product->saveOrFail();
        return $product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product) {
        $product->delete();
        return ['ok' => true];
    }

    public function addSelectableType(Request $request, Product $product) {
        $validated = $this->validate($request, [
            'name' => 'required|string'
        ]);

        return $product->selectableTypes()->create([
            'name' => $validated['name']
        ])->load('items');
    }

    public function addBrand(Request $request, Product $product) {
        $validated = $this->validate($request, [
            'brand_id' => 'required|numeric|exists:App\Models\Brand,id',
        ]);

        $product->brand_id = $validated['brand_id'];
        $product->saveOrFail();
        return $product;
    }

    public function deleteBrand(Request $request, Product $product) {
        $product->brand_id = null;
        $product->saveOrFail();
        return $product;
    }

    public function uploadCover(Request $request) {
        $validated = $this->validate($request, [
            'cover' => 'required|file|mimes:jpg,png,jpeg,webp'
        ]);
        $coverFile = $request->file('cover');
        $coverHash = \Str::random(18);

        $thumbs = [
            '100' => Image::make($request->file('cover'))->resize(100, 100),
            '200' => Image::make($request->file('cover'))->resize(200, 200),
            '300' => Image::make($request->file('cover'))->resize(300, 300),
            '400' => Image::make($request->file('cover'))->resize(400, 400),
            '500' => Image::make($request->file('cover'))->resize(500, 500)
        ];
        foreach ($thumbs as $key => $thumb) {
            Storage::disk('public')->put(
                'products/' . $coverHash . '/' . $key . '.' . $coverFile->guessExtension(),
                (string)$thumb->encode()
            );
        }

        $coverPath = Storage::disk('public')->putFileAs('products/' . $coverHash, $coverFile, 'original.' . $coverFile->guessExtension());

        return [
            'ok' => true,
            'cover' => $coverPath,
        ];
    }
}
