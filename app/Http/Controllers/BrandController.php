<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Intervention\Image\Facades\Image;

class BrandController extends Controller {
    public function __construct() {

        $this->middleware('auth:admin')->except(['products', 'activeIndex']);
    }
    public function index() {
        return Inertia::render('Admin/brands/ListBrand', [
            'brands' => Brand::paginate(24),
        ]);
    }
    public function activeIndex(Request $request) {
        $brands = Brand::where('status', 1);
        if ($request->has('paginate')) {
            return $brands->paginate($request->get('per_page') ?? 20);
        }
        return $brands->get();
    }
    public function create() {
        //
    }

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
        $logo = Image::make($request->file('logo'))->resize(500, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        $logoHash = $request->file('logo')->hashName();


        Storage::disk('public')->put('brands/cover/' . $logoHash, ((string)$logo->encode()));
        Storage::disk('public')->put('brands/cover/thumb/' . $logoHash,
            (string)Image::make($request->file('logo'))->resize(200, null, function ($cons) {
                $cons->aspectRatio();
            })->encode()
        );


        Brand::create([
            'name' => $validated['name'],
            'name_en' => $validated['name_en'],
            'slug' => \Str::slug($validated['name_en']),
            'logo' => $logoHash,
            'status' => $validated['status'],
            'description' => $validated['description'],
        ]);
        return $this->index();
    }
    public function show(Brand $brand) {
        return Inertia::render('Admin/brands/EditBrand', [
            'brand' => $brand,
        ]);
    }
    public function edit($id) {
        //
    }
    public function update(Request $request, Brand $brand) {
        $validated = $this->validate($request, [
            'name' => 'required|string',
            'name_en' => 'required|string',
            'status' => 'required|string',
            'description' => 'sometimes|string',
        ]);

        if ($request->hasFile('logo')) {
            $this->validate($request, [
                'logo' => 'required|file|mimes:jpg,jpeg,png'
            ]);
            $logo = Image::make($request->file('logo'))->resize(500, null, function ($const) {
                $const->aspectRatio();
            });
            $logoHash = $request->file('logo')->hashName();


            Storage::disk('public')->put('brands/cover/' . $logoHash, ((string)$logo->encode()));
            Storage::disk('public')->put('brands/cover/thumb/' . $logoHash, (string)Image::make(
                $request->file('logo')
            )->resize(200, null, function ($const) {
                $const->aspectRatio();
            })->encode());


            $brand->logo = $logoHash;
        }

        $brand->name = $validated['name'];
        $brand->name_en = $validated['name_en'];
        $brand->status = ($validated['status'] == '1' || $validated['status'] == 'true');
        $brand->description = $validated['description'] ?? null;

        $brand->save();

        return Inertia::render('Admin/brands/EditBrand', [
            'brand' => $brand,
        ]);
    }
    public function destroy($id) {
        //
    }
    public function products(Request $request, Brand $brand) {
        if (!$brand->status) {
            return response()->json([
                'ok' => false,
                'message' => 'شما مجاز به مشاهده محصولات این برند نیستید'
            ]);
        }
        $products = $brand->products()->where('status', 1);

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
