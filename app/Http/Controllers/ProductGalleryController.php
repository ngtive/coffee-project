<?php

namespace App\Http\Controllers;

use App\Models\ProductGallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProductGalleryController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
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
        $this->validate($request, [
            'file' => 'required|file|mimes:jpg,jpeg,png,webp,sgv',
        ]);

        $file = $request->file('file');
        $hashName = $file->hashName();


        $thumbs = [];
        for ($i = 100; $i <= 800; $i += 100) {
            $thumbs[(string)$i] = Image::make($file)->resize($i, function ($const) {
                $const->aspectRatio();
            });
        }

        foreach ($thumbs as $key => $thumb) {
            Storage::disk('public')->put(
                'products/gallery/' . $key . '/' . $hashName,
                (string)$thumb->encode()
            );
        }

        $filePath = Storage::disk('public')->putFileAs('products/gallery/org', $file, $hashName);

        return ProductGallery::create([
            'file' => $hashName,
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
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
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, ProductGallery $productGallery) {
        $productGallery->deleteOrFail();
        return response()->json(['ok' => true]);
    }
}
