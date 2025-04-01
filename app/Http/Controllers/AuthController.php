<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // อย่าลืมนำเข้ารุ่น User ถ้าจะใช้สร้าง user

class AuthController extends Controller
{
    /**
     * แสดงหน้าฟอร์ม Register
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * จัดการลงทะเบียนผู้ใช้ใหม่
     */
    public function register(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:4'
        ]);


        $user = new User();
        $user->name = $validatedData['name'];
        $user->username = $validatedData['username'];
        // เข้ารหัสรหัสผ่านก่อนบันทึก
        $user->password = Hash::make($validatedData['password']);
        $user->save();


        Auth::login($user);


        return redirect()->route('home.index')
            ->with('success', 'ลงทะเบียนสำเร็จ!');
    }

    /**
     * แสดงหน้า Login (Form)
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * ประมวลผลเมื่อผู้ใช้ submit ฟอร์ม Login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required|min:4'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // Redirect ไปยังหน้า orders.index หลังจากล็อกอินสำเร็จ
            return redirect()->intended(route('home.index'));
        }
        return back()->withErrors([
            'username' => 'ชื่อผู้ใช้ไม่ถูกต้อง',
            'password' => 'รหัสผ่านไม่ถูกต้อง',
        ]);
    }

    /**
     * ออกจากระบบ (Logout)
     */
    public function logout(Request $request)
    {
        Auth::logout();

        // เคลียร์ session เก่า
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
