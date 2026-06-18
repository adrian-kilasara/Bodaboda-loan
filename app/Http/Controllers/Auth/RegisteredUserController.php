<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\DriverProfile;
use App\Models\OwnerProfile;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:150'],
            'email'    => ['required', 'string', 'email', 'max:150', 'unique:users'],
            'phone'    => ['required', 'string', 'max:20', 'unique:users'],
            'role'     => ['required', 'in:owner,driver'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
            'status'   => 'active',
        ]);

        if ($user->role === 'owner') {
            OwnerProfile::create(['user_id' => $user->id]);
        } else {
            DriverProfile::create(['user_id' => $user->id]);
        }

        event(new Registered($user));
        Auth::login($user);

        return $this->redirectByRole($user);
    }

    private function redirectByRole(User $user): RedirectResponse
    {
        return match($user->role) {
            'owner'  => redirect()->route('owner.dashboard'),
            'driver' => redirect()->route('driver.dashboard'),
            'admin'  => redirect()->route('admin.dashboard'),
            default  => redirect('/'),
        };
    }
}
