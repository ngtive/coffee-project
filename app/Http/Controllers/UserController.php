<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\Request;

class UserController extends Controller {
    public function __construct() {
        $this->middleware('auth:admin')->only(['index', 'destroy', 'update', 'show']);
        $this->middleware('auth:api')->only(['logout', 'showMe']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $users = User::query();

        if ($request->has('search')) {
            $validated = $this->validate($request, [
                'search' => 'required|string',
            ]);

            $search = $validated['search'];
            $users->where(function ($query) use ($search) {
                $query->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere('phone_number', 'like', '%' . $search . '%');
            });
        }

        $users = $users->paginate(24);

        return inertia('Admin/users/UsersList', compact('users'));
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
    public function show(Request $request, User $user) {
        return $user->load(['addresses.city', 'addresses.province', 'orders']);
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
    public function update(Request $request, User $user) {
        $validated = $this->validate($request, [
            'first_name' => 'sometimes|string',
            'last_name' => 'sometimes|string',
            'phone_number' => 'sometimes|numeric',
        ]);

        if (isset($validated['first_name'])) {
            $user->first_name = $validated['first_name'];
        }
        if (isset($validated['last_name'])) {
            $user->last_name = $validated['last_name'];
        }
        if (isset($validated['phone_number'])) {
            $user->phone_number = $validated['phone_number'];
        }

        $user->saveOrFail();
        return $user;
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

    public function userLogin(Request $request) {
        $validated = $this->validate($request, [
            'mobile' => 'required|regex:/(09)[0-9]{9}/|digits:11|numeric'
        ]);

        $mobile = $validated['mobile'];
        if (!User::where('phone_number', $mobile)->exists()) {
            $user = User::create(['phone_number' => $mobile]);
        } else {
            $user = User::where('phone_number', $mobile)->firstOrFail();
        }

        VerificationCode::create(['phone_number' => $mobile, 'code' => '1111', 'expire_at' => now()->addHour()]);

        return ['ok' => true, 'message' => '???? ?????????? ?????????? ????'];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        //
    }

    public function verifyLogin(Request $request) {
        $validated = $this->validate($request, [
            'mobile' => 'required|regex:/(09)[0-9]{9}/|digits:11|numeric',
            'code' => 'required|string|numeric',
        ]);


        $mobile = $validated['mobile'];
        $verifyCode = VerificationCode::where('phone_number', $mobile)->latest()->first();

        if (!$verifyCode) {
            return response()->json([
                'ok' => false,
                'message' => '???? ???????????? ???????? ?????? ?????????? ???????? ?????????? ???????? ??????'
            ]);
        }

        if ($verifyCode->is_expired) {
            return response()->json([
                'ok' => false,
                'message' => '???? ?????????? ?????????? ?????? ??????',
            ])->setStatusCode('400');
        }


        if ($verifyCode->code == $validated['code']) {
            $user = User::where('phone_number', $validated['mobile'])->firstOrFail();

            return response()->json([
                'ok' => true,
                'token' => $user->createToken('login')->plainTextToken,
                'message' => '???? ???????????? ???????? ????????'
            ]);
        } else {
            return response()->json([
                'ok' => false,
                'message' => '???? ?????????? ???????????? ??????'
            ]);
        }
    }

    public function logout(Request $request) {
        $user = $request->user();
        if (!$user) {
            return;
        }

        $user->tokens()->delete();
        return ['ok' => true];
    }
    public function showMe(Request $request) {
        $user = $request->user();
        if ($request->has('withAddresses')) { $user->load('addresses'); }
        if ($request->has('withCartItems')) { $user->load('cartItems'); }
        if ($request->has('withOrders')) { $user->load('orders'); }

        return $user;
    }

    public function updateProfile(Request $request) {
        $user = $request->user();

        $validated = $this->validate($request, [
            'first_name' => 'sometimes|string',
            'last_name' => 'sometimes|string',
            'email' => 'sometimes|email',
        ]);

        $user->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email']
        ]);
        return $user;
    }
}
