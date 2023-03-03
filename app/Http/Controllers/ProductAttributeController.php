<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\ProductAttribute;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductAttributeController extends Controller {
    public function index() {
        $attributes = Attribute::all()->load('values');
        return Inertia::render('Admin/attributes/ListAttribute', compact('attributes'));
    }

    public function store(Request $request) {
        //
    }

    public function show(Request $request, Attribute $attribute) {
        $attribute->load('values');
        return inertia('Admin/attributes/ListAttributeValues', ['attribute' => $attribute]);
    }

    public function edit($id) {
        //
    }

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

    public function destroy(ProductAttribute $productAttribute) {
        $productAttribute->attributeValues()->detach();
        $productAttribute->deleteOrFail();
        return $productAttribute;
    }

    public function create() {
        //
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
        $attribute->values()->create($validated);

        return redirect()->route('attributes.show', ['attribute' => $attribute->id]);
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
