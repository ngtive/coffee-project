<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSpecification;
use Illuminate\Http\Request;

class ProductSpecificationController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        if ($request->has('product_id')) {
            $validated = $this->validate($request, [
                'product_id' => 'exists:App\Models\Product,id',
            ]);

            return Product::find($validated['product_id'])->specifications;
        }
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
            'value' => 'required|string',
            'product_id' => 'sometimes|exists:App\Models\Product,id'
        ]);


        return ProductSpecification::create([
            'name' => $validated['name'],
            'value' => $validated['value'],
            'product_id' => $validated['product_id'] ?? null,
        ]);
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
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(ProductSpecification $productSpecification) {
        return $productSpecification;
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
    public function destroy($id) {
        //
    }
}
