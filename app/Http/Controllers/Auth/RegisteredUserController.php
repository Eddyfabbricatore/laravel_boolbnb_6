<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $messages = 
        [
            "name.max" => 'Il nome non può avere più di :max caratteri',
            "surname.max" => 'Il cognome non può avere più di :max caratteri',
            "email.required" => 'Inserisci l\'email',
            "email.string" => 'L\'email deve essere una stringa di caratteri',
            "email.lowercase" => 'I caratteri devono essere minuscoli',
            "email.email" => 'Inserisci un email valida',
            "email.max" => 'L\'email non deve avere più di :max caratteri',
            "email.unique" => 'L\'email è gia registrata',
            "password.required" => 'Inserisci la password',
            "password.confirmed" => 'Le due password devono coincidere',
            "password.min" => 'La password deve avere almeno :min caratteri',
            "date_of_birth.date" => 'Inserisci una data di nascita corretta'
        ]; 

        $request->validate([
            'name' => ['max:255'],
            'surname' => ['max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', 'min:8', Rules\Password::defaults()]
        ], $messages);          

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'date_of_birth' => $request->date_of_birth
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
