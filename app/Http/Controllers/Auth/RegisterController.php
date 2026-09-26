<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'nim' => ['required', 'numeric', 'digits_between:8,15', 'unique:mahasiswa,nim'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'mahasiswa',
            'status' => 'inactive',
        ]);

        $slug = \Illuminate\Support\Str::slug($data['name']);
        $originalSlug = $slug;
        $count = 1;
        while (\App\Models\Mahasiswa::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        \App\Models\Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => $data['nim'],
            'nama_lengkap' => $data['name'],
            'prodi' => '-',
            'angkatan' => date('Y'),
            'slug' => $slug,
        ]);

        app(\App\Services\AuditLogService::class)->log(
            'USER_REGISTER',
            "User '{$user->name}' (NIM: {$data['nim']}) registered successfully as inactive.",
            $user->id
        );

        return $user;
    }

    /**
     * The user has been registered.
     */
    protected function registered(\Illuminate\Http\Request $request, $user)
    {
        $this->guard()->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Akun berhasil dibuat dan sedang menunggu persetujuan Admin.');
    }
}
