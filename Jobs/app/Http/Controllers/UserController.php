<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function create() {
        return view('users.register', ['pageTitle' => 'Register']);
    }

    public function store(Request $request) {
        //Other way to validate is $this->validate($request, array)
        $formField = $request->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', 'min:6'],
            'g-recaptcha-response' => 'required|captcha'
        ]);

        // Hash Password
        // We can also hash password using Hash::make($formField['password'])
        $formField['password'] = bcrypt($formField['password']);

        // Create User
        unset($formField['g-recaptcha-response']);
        $user = User::create($formField);

        // Login
        auth()->login($user);

        return redirect('/')->with('message', 'User created and logged in!');
    }
    
    public function logout() {
        auth()->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/')->with('message', 'You have been logged you!');
    }

    public function login() {
        return view('users.login', ['pageTitle' => 'Login']);
    }

    public function authenticate(Request $request) {
        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required',
            'g-recaptcha-response' => 'required|captcha'
        ]);
        
        unset($formFields['g-recaptcha-response']);
        // We can also get the date from the request directly after validation without the captcha field ( $request->only('email', 'password') ), we can pass second parament for Rememeber Me option
        if(auth()->attempt($formFields)) {
            request()->session()->regenerate();
            return redirect('/')->with('message', 'You have logged in!');
        }

        return back()->withErrors(['email' => 'Invalid Credntials'])->onlyInput('email'); // we can also flash an error message with " Session "
    }
}
