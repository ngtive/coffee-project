<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;

class AddressController extends Controller {

    public function __construct() {
        $this->middleware('auth:api');
    }

    public function addressList() {
        $addresses = Address::query();
        return $addresses->get();
    }

    public function showUserAddresses(Request $request, User $user) {
        return $user->addresses;
    }

    public function latest(Request $request) {
        $user = $request->user();
        return $user->addresses()->latest()->first();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        return $request->user()->addresses;
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
            'address' => 'required|string',
            'receiver' => 'required|string',
            'mobile' => 'required|string',
            'province_id' => 'required|numeric|exists:App\Models\Province,id',
            'city_id' => 'required|numeric|exists:App\Models\City,id',
            'postal_code' => 'required|numeric',
            'telephone' => 'sometimes|nullable|string',

        ]);

        $user = $request->user();

        return $user->addresses()->create([
            'address' => $validated['address'],
            'receiver' => $validated['receiver'],
            'postal_code' => $validated['postal_code'],
            'telephone' => $validated['telephone'] ?? null,
            'mobile' => $validated['mobile'],
            'province_id' => $validated['province_id'],
            'city_id' => $validated['city_id'],
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Address $address) {
        return $request->user()->addresses()->where('id', $address->id)->firstOrFail();
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
