<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller {
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


    public function authenticateWithEmail(Request $request) {
        $validated = $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string',
            'remember' => 'required|boolean',
        ]);

        $admin = Admin::where('email', $validated['email'])->first();


        if (!$admin || !(Hash::check($validated['password'], $admin->password))) {
            return throw \Illuminate\Validation\ValidationException::withMessages([
                'password' => 'ایمیل یا رمز عبور اشتباه است و با مشخصات ما سازگاری ندارد'
            ]);
        }

        return response()->json([
            'ok' => true,
            'message' => 'ورود موفق',
            'user' => $admin,
            'token' => $admin->createToken('login', ['*'], $validated['remember'] ? null : now()->subHour())->plainTextToken,
        ]);
    }

    public function currentAccessToken(Request $request) {
        $accessToken = $request->user()->currentAccessToken();

        return [

        ];
    }

    public function currentUser(Request $request) {
        return $request->user();
    }

    public function logout(Request $request) {
        $request->user()->tokens()->delete();
        return ['ok' => true];
    }

    public function adminHome(Request $request) {
        return view('admin.home.index');
    }
}
