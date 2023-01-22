<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller {
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
        //
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
    public function update(Request $request, ProductAttribute $productAttribute) {
        $validated = $this->validate($request, [
            'quantity' => 'required|numeric',
            'amount' => 'required|numeric',
            'weight' => 'required|numeric',
        ]);

        $productAttribute->updateOrFail($validated);
        return $productAttribute;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductAttribute $productAttribute) {
        $productAttribute->attributeValues()->detach();
        $productAttribute->deleteOrFail();
        return $productAttribute;
    }

    public function storeAttribute(Request $request) {
        $validated = $this->validate($request, [
            'name' => 'required|string|max:50'
        ]);
        return Attribute::create(['name' => $validated['name']]);
    }

    public function indexAttribute(Request $request) {
        return Attribute::all()->load('values');
    }

    public function showAttribute(Request $request, Attribute $attribute) {
        return $attribute->load('values');
    }

    public function storeAttributeValue(Request $request, Attribute $attribute) {
        $validated = $this->validate($request, [
            'value' => 'required|string'
        ]);
        return $attribute->values()->create($validated);
    }

    public function deleteAttribute(Request $request, Attribute $attribute) {
        return $attribute->deleteOrFail();
    }

    public function deleteAttributeValue(Request $request, AttributeValue $value) {
        return $value->deleteOrFail();
    }

    public function storeProductAttribute(Request $request, Product $product) {
        $validated = $this->validate($request, [
            'quantity' => 'required|numeric',
            'amount' => 'required|numeric',
            'weight' => 'required|numeric',
            'values.*' => 'required|numeric|exists:App\Models\AttributeValue,id'
        ]);

        if ($request->has('weight')) {
            $this->validate($request, [
                'weight' => 'required|numeric',
            ]);
            $validated['weight'] = $request->get('weight');
        } else {
            $validated['weight'] = $product->weight;
        }

        $productAttribute = $product->productAttributes()->create([
            'quantity' => $validated['quantity'],
            'amount' => $validated['amount'],
            'weight' => $validated['weight']
        ]);

        $productAttribute->attributeValues()->attach($validated['values']);
        return $productAttribute->load('attributeValues.attribute');
    }
}
