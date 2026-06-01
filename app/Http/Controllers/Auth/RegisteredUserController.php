<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
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
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'ic_type' => ['required', 'string', 'in:Baru,Lama'],
            'ic_number' => ['required', 'string', 'regex:/^[0-9]{6}-[0-9]{2}-[0-9]{4}$/', 'unique:'.User::class],
            'date_of_birth' => ['required', 'date'],
            'place_of_birth' => ['required', 'string'],
            'marital_status' => ['required', 'string'],
            'race' => ['required', 'string'],
            'religion' => ['required', 'string'],
            'citizen' => ['required', 'string'],
            'address_line1' => ['required', 'string'],
            'address_line2' => ['nullable', 'string'],
            'address_line3' => ['nullable', 'string'],
            'city' => ['required', 'string'],
            'postcode' => ['required', 'string', 'regex:/^[0-9]{5}$/'],
            'district' => ['required', 'string'],
            'state_nation' => ['required', 'string'],
            'phone_number' => ['required', 'string', 'regex:/^\+60[0-9]+$/'],
            'phone_home' => ['nullable', 'string', 'regex:/^\+60[0-9]+$/'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'ic_type' => $request->ic_type,
            'ic_number' => $request->ic_number,
            'date_of_birth' => $request->date_of_birth,
            'place_of_birth' => $request->place_of_birth,
            'marital_status' => $request->marital_status,
            'race' => $request->race,
            'religion' => $request->religion,
            'citizen' => $request->citizen,
            'address_line1' => $request->address_line1,
            'address_line2' => $request->address_line2,
            'address_line3' => $request->address_line3,
            'city' => $request->city,
            'postcode' => $request->postcode,
            'district' => $request->district,
            'state_nation' => $request->state_nation,
            'phone_number' => $request->phone_number,
            'phone_home' => $request->phone_home,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
