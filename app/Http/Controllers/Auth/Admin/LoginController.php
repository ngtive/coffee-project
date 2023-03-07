<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Morilog\Jalali\Jalalian;

class LoginController extends Controller {
    public function __construct() {
        $this->middleware('auth:admin')->only(['logout', 'adminHome']);
    }

    public function index(Request $request) {
        return Inertia::render('Admin/Login');
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
        Auth::guard('admin')->login($admin, true);
        return redirect()->route('admin.home');
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
        Auth::guard('admin')->logout();
        return redirect()->route('admin.home');
    }

    public function adminHome(Request $request) {

        $nowShamsi = Jalalian::now();

        $todayOrders = Order::query()->whereBetween('created_at', [
            Carbon::now()->startOfDay(),
            now(),
        ])->whereNotNull('paid_at')->count();

        $thisMonthOrders = Order::query()->whereBetween(
            'created_at',
            [
                (new Jalalian($nowShamsi->getYear(), $nowShamsi->getMonth(), $nowShamsi->getDay()))->toCarbon()->startOfDay(),
                now()
            ],
        )->whereNotNull('paid_at')->count();

        $thisWeekOrders = Order::query()->whereBetween('created_at', [
            Carbon::now()->startOfWeek(Carbon::SATURDAY),
            now(),
        ])->whereNotNull('paid_at')->count();

        return Inertia::render('Home', [
            'stats' => [
                'today' => $todayOrders,
                'month' => $thisMonthOrders,
                'week' => $thisWeekOrders,
            ]
        ]);
    }
}
