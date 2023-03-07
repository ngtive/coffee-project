<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductGallery;
use App\Models\ProductSpecification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Intervention\Image\Facades\Image;

class ProductController extends Controller {

    public function __construct() {
//        $this->middleware(['auth:admin-api'])->except(['index', 'show']);

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

    public function showStoreForm(Request $request) {
        return Inertia::render('Admin/products/NewProduct', [
            'categories' => Category::all()->map(function ($item) {
                $item['selected'] = false;
                return $item;
            }),
            'brands' => Brand::all(),
            'attributes' => Attribute::all()->map(function ($item) {
                $item['selected_value'] = null;
                return $item;
            })->load('values'),
        ]);
    }

    public function store(Request $request) {
        $validated = $this->validate($request, [
            'title' => 'required|string|max:200',
            'title_en' => 'required|string|max:200',
            'cover' => 'required|string',
            'status' => 'required|string',
            'categories.*' => 'required|nullable|numeric|exists:App\Models\Category,id',
            'brand' => 'sometimes|numeric|exists:App\Models\Brand,id',
            'description' => 'sometimes|nullable|string',
        ]);

        $validated['status'] = ($validated['status'] == '1' || $validated['status'] == 'true');

        $product = new Product();

        $product->title = $validated['title'];
        $product->title_en = $validated['title_en'];
        $product->cover = $request->get('cover');
        $product->slug = \Str::slug($validated['title_en']);
        $product->status = $validated['status'];
        $product->description = $validated['description'] ?? null;

        if ($request->has('categories') && count($request->get('categories')) > 0) {
            $product->categories()->attach($request->get('categories'));
        }
        if ($request->has('brand') && !is_null($request->get('brand'))) {
            $product->brand_id = $request->get('brand');
        }
        if ($request->has('specifications') && !is_null($request->get('specifications')) && count($request->get('specifications')) > 0) {
            $this->validate($request, [
                'specifications.*' => 'required|numeric|exists:App\Models\ProductSpecification,id',
            ]);


            ProductSpecification::whereIn('id', $request->get('specifications'))->updateOrFail([
                'product_id' => $product->id,
            ]);
        }
        if ($request->has('description') && !is_null($request->description)) {
            $product->description = $request->get('description');
        }
        if ($request->has('image_galleries') && !is_null($request->image_galleries) && count($request->image_galleries) > 0) {
            $this->validate($request, [
                'image_galleries.*' => 'required|numeric|exists:App\Models\ProductGallery,id',
            ]);

            ProductGallery::whereIn('id', $request->image_galleries)
                ->update([
                    'product_id' => $product->id,
                ]);
        }

//      If product doesn't have product attribute some properties are required
        if ($request->has('product_attributes') && !is_null($request->get('product_attributes')) && count($request->get('product_attributes')) > 0) {
            $this->validate($request, [
                'product_attributes.*' => 'required|numeric|exists:App\Models\ProductAttribute,id',
            ]);
            ProductAttribute::whereIn('id', $request->get('product_attributes'))
                ->updateOrFail($request->get('product_attributes'));

        } else {
            $this->validate($request, [
                'price' => 'required|numeric',
                'weight' => 'required|numeric',
                'quantity' => 'required|numeric'
            ]);

            $product->amount = $request->get('price');
            $product->weight = $request->get('weight');
            $product->quantity = $request->get('quantity');
        }
        $product->saveOrFail();
//        End flow;


        return $this->index($request);

    }

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

        return Inertia::render('Admin/products/ProductsList', [
            'products' => $productQuery->paginate($request->get('per_page') ?? 24)->withQueryString()
        ]);
    }

    public function show(Request $request, Product $product) {
        $product->load(['productAttributes.attributeValues.attribute', 'gallery', 'specifications', 'categories']);

        if ($request->has('withCategories')) {
            $product->load(['categories'])->only('categories.id');
        }

        $product['categories'] = $product->categories->map(function ($category) {
            return $category->id;
        });

        return Inertia::render('Admin/products/EditProduct', [
            'product' => $product,
            'categories' => Category::all()->map(function ($item) {
                $item['selected'] = false;
                return $item;
            }),
            'brands' => Brand::all(),
            'attributes' => Attribute::all()->map(function ($item) {
                $item['selected_value'] = null;
                return $item;
            })->load('values'),
        ]);
    }

    public function showProduct(Request $request, Product $product) {
        if (!$product->status) {
            return response()->json([
                'ok' => false,
                'message' => 'شما مجاز به مشاهده این اطلاعات نیستید',
            ])->setStatusCode(403);
        }
    }

    public function edit($id) {
        //
    }

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

    public function create() {
        //
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
