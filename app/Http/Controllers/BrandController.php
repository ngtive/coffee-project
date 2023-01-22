<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class BrandController extends Controller {

    public function __construct() {

        $this->middleware('auth:admin-api')->except(['index', 'show', 'products']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return Brand::all();
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
            'logo' => 'required|file|mimes:jpg,png,jpeg',
            'status' => 'required|string',
            'description' => 'sometimes|nullable|string'
        ]);

        if ($request->has('status')) {
            $validated['status'] = ($request->status == '1' || $request->status == 'true');
        }
        $logo = Image::make($request->file('logo'))->resize(500, 500);

        $logoHash = $request->file('logo')->hashName();
        Storage::disk('public')->put('brands/cover/' . $logoHash, ((string)$logo->encode()));


        return Brand::create([
            'name' => $validated['name'],
            'name_en' => $validated['name_en'],
            'slug' => \Str::slug($validated['name_en']),
            'logo' => 'brands/cover/' . $logoHash,
            'status' => $validated['status'],
            'description' => $validated['description'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand) {
        return $brand;
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
    public function update(Request $request, Brand $brand) {
        $validated = $this->validate($request, [
            'name' => 'required|string',
            'name_en' => 'required|string',
            'status' => 'required|string',
            'description' => 'required|string',
        ]);

        if ($request->hasFile('logo')) {
            $this->validate($request, [
                'logo' => 'required|file|mimes:jpg,jpeg,png'
            ]);
            $logo = Image::make($request->file('logo'))->resize(500, 500);
            $logoHash = $request->file('logo')->hashName();
            Storage::disk('public')->put('brands/cover/' . $logoHash, ((string)$logo->encode()));
            $brand->logo = 'brands/cover/' . $logoHash;
        }

        $brand->name = $validated['name'];
        $brand->name_en = $validated['name_en'];
        $brand->status = ($validated['status'] == '1' || $validated['status'] == 'true');
        $brand->description = $validated['description'];

        $brand->save();
        return $brand;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

    public function products(Request $request, Brand $brand) {
        $products = $brand->products();

        if ($request->has('sort')) {
            $this->validate($request, [
                'sort' => 'required|string|max:20'
            ]);
            $sort = $request->get('sort');

            switch ($sort) {
                case 'newest':
                    $products->orderBy('created_at', 'desc');
                    break;
                case 'most-wanted';
                    break;
                default:
                    break;
            }
        }


        if ($request->has('paginate')) {
            return $products->paginate(20);
        }

        return $products->get();
    }
}
