<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\AttributeValue;
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

        if ($request->has('product_id')) {
            $this->validate($request, [
                'product_id' => 'required|numeric|exists:App\Models\Product,id'
            ]);
            $validated['product_id'] = $request->get('product_id');
        }

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
            'name' => 'required|string|max:50',
            'is_color' => 'required|boolean',
            'is_weight' => 'required|boolean',
        ]);
        $attribute = [
            'name' => $validated['name'],
            'is_color' => $validated['is_color'],
            'is_weight' => $validated['is_weight'],
        ];
        return Attribute::create($attribute);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    public function indexAttribute(Request $request) {
        return Attribute::all()->load('values');
    }

    public function showAttribute(Request $request, Attribute $attribute) {
        $attribute->load('values');
        return $attribute;
    }

    public function storeAttributeValue(Request $request, Attribute $attribute) {
        $validated = $this->validate($request, [
            'value' => 'required',
            'is_color' => 'required|boolean',
            'is_weight' => 'required|boolean',
        ]);
        return $attribute->values()->create($validated);
    }

    public function deleteAttribute(Request $request, Attribute $attribute) {
        return $attribute->deleteOrFail();
    }

    public function deleteAttributeValue(Request $request, AttributeValue $value) {
        return $value->deleteOrFail();
    }

    public function storeProductAttribute(Request $request) {
        $validated = $this->validate($request, [
            'quantity' => 'required|numeric',
            'amount' => 'required|numeric',
            'weight' => 'required|numeric',
        ]);

        if ($request->has('weight')) {
            $validated['weight'] = $request->get('weight');
        }

        $productAttribute = ProductAttribute::create([
            'quantity' => $validated['quantity'],
            'amount' => $validated['amount'],
            'weight' => $validated['weight']
        ]);

        if ($request->has('values')) {
            $productAttribute->attributeValues()->detach();
            $productAttribute->attributeValues()->attach($request->get('values'));
        }

        $productAttribute->load('attributeValues.attribute');
        return $productAttribute;
    }
}
