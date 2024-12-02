<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fullName' => 'required|string|max:255',
            'userName' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[0-9]/',                // At least one number
                'regex:/[!@#$%^&*(),.?":{}|<>]/', // At least one special character
                'confirmed',
            ],
        ], [
            'fullName.required' => 'Nama lengkap harus diisi.',
            'userName.required' => 'Email harus diisi.',
            'userName.email' => 'Email harus berupa email yang valid.',
            'userName.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password harus berisi minimal 8 karakter.',
            'password.regex' => 'Password harus mengandung setidaknya satu angka dan satu karakter khusus.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        if ($validator->fails()) {
            return redirect('/register')
                ->withErrors($validator)
                ->withInput();
        }

        User::create([
            'name' => $request->fullName,
            'email' => $request->userName,
            'password' => bcrypt($request->password),
        ]);

        return redirect('/register')->with('success', 'Pendaftaran berhasil! Silakan login.');
    }
}
