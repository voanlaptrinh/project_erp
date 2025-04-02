<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function showform()
    {
        if(\Auth::check()){
            return redirect()->route('home'); // Chuyển hướng đến trang chính
        }
        $no_layout = true;
        return view('login', compact('no_layout'));
    }
    public function login(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $credentials = $request->only('email', 'password');

        // Kiểm tra thông tin đăng nhập
        if (\Auth::attempt($credentials)) {
            // Nếu thành công, chuyển hướng đến trang chủ
            return redirect()->intended('/home');
        }

        // Nếu đăng nhập thất bại, quay lại form với thông báo lỗi
        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không đúng.',
        ]);
    }
    public function logout(Request $request)
    {
        \Auth::logout();  // Hủy phiên đăng nhập của người dùng

        $request->session()->invalidate();  // Hủy phiên làm việc

        $request->session()->regenerateToken();  // Tạo lại token CSRF

        return redirect('/');  // Chuyển hướng người dùng về trang login
    }
}
