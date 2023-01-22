<?php

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function sendVerificationCode(Request $request) {
        $validated = $this->validate($request, [
            'phone_number' => ['required', 'string']
        ]);

        $code = random_int(1000, 9999);
        VerificationCode::create([
            'code' => $code,
            'retry_at' => now()->subMinutes(10),
            'phone_number' => $validated['phone_number'],
        ]);
        return response()->json([
            'ok' => true,
            'message' => 'کد تایید ارسال شد',
        ]);
    }

    public function verifyCode(Request $request) {
        $validated = $this->validate($request, [
            'code' => [
                'required',
                'string',
                'size:4'
            ],
            'phone_number' => 'required|string'
        ]);


        $code = $validated['code'];
        $verifyCode = VerificationCode::where('phone_number', $validated['phone_number'])
            ->latest()->firstOrFail();

        if ($verifyCode->retry_at < now()) {
            throw ValidationException::withMessages([
                'code' => 'اعتبار کد شما به پایان رسیده است لطفا کد جدید ارسال کنید'
            ]);
        }

        if ($verifyCode->code != $code) {
            throw ValidationException::withMessages([
                'code' => 'کد ارسال شده با کد وارد شده مطابقت ندارد'
            ]);
        }


        $findUserQuery = User::where('phone_number', $validated['phone_number']);
        if ($findUserQuery->exists()) {
            $user = $findUserQuery->firstOrFail();
        } else {
            $user = User::create([
                'phone_number' => $validated['phone_number'],
            ]);
        }

        \Auth::guard('api')->login($findUserQuery->firstOrFail());
        return response()->json([
            'ok' => true,
            'message' => 'ورود موفق'
        ]);
    }


}
