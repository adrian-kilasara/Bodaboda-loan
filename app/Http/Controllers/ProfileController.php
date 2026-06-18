<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $profile = $user->driverProfile;
        return view('driver.profile', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'                   => ['required', 'string', 'max:150'],
            'phone'                  => ['required', 'string', 'max:20'],
            'driving_license_number' => ['nullable', 'string', 'max:50'],
            'national_id'            => ['nullable', 'string', 'max:50'],
            'physical_address'       => ['nullable', 'string', 'max:255'],
        ]);

        $user->update(['name' => $request->name, 'phone' => $request->phone]);

        $user->driverProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'driving_license_number' => $request->driving_license_number,
                'national_id'            => $request->national_id,
                'physical_address'       => $request->physical_address,
            ]
        );

        return back()->with('success', 'Profile updated.');
    }
}
