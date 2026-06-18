<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;
use App\Models\Contract;
use App\Models\User;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::where('owner_id', auth()->id())
            ->with(['driver', 'contract'])
            ->latest()
            ->paginate(15);

        return view('contacts.index', compact('contacts'));
    }

    public function create()
    {
        $contracts = Contract::where('owner_id', auth()->id())
            ->whereIn('status', ['active', 'pending_enrolment'])
            ->with('motorcycle')
            ->get();

        $drivers = User::where('role', 'driver')
            ->whereHas('driverContracts', fn($q) => $q->where('owner_id', auth()->id()))
            ->get();

        return view('contacts.create', compact('contracts', 'drivers'));
    }

    public function store(StoreContactRequest $request)
    {
        Contact::create([
            ...$request->validated(),
            'owner_id' => auth()->id(),
        ]);

        return redirect()->route('contacts.index')->with('success', 'Contact saved.');
    }

    public function destroy(Contact $contact)
    {
        if ($contact->owner_id !== auth()->id()) abort(403);
        $contact->delete();
        return back()->with('success', 'Contact removed.');
    }
}
