<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMotorcycleRequest;
use App\Http\Requests\UpdateMotorcycleRequest;
use App\Models\Motorcycle;
use Illuminate\Http\Request;

class MotorcycleController extends Controller
{
    public function index()
    {
        $motorcycles = Motorcycle::where('owner_id', auth()->id())
            ->withCount('contracts')
            ->latest()
            ->paginate(15);

        return view('motorcycles.index', compact('motorcycles'));
    }

    public function create()
    {
        return view('motorcycles.create');
    }

    public function store(StoreMotorcycleRequest $request)
    {
        $motorcycle = Motorcycle::create([
            ...$request->validated(),
            'owner_id' => auth()->id(),
            'status'   => 'available',
        ]);

        return redirect()->route('motorcycles.show', $motorcycle)
            ->with('success', 'Motorcycle registered successfully.');
    }

    public function show(Motorcycle $motorcycle)
    {
        $this->authorize('view', $motorcycle);
        $motorcycle->load(['contracts.driver', 'contracts.installments']);
        return view('motorcycles.show', compact('motorcycle'));
    }

    public function edit(Motorcycle $motorcycle)
    {
        $this->authorize('update', $motorcycle);
        return view('motorcycles.edit', compact('motorcycle'));
    }

    public function update(UpdateMotorcycleRequest $request, Motorcycle $motorcycle)
    {
        $this->authorize('update', $motorcycle);
        $motorcycle->update($request->validated());
        return redirect()->route('motorcycles.show', $motorcycle)
            ->with('success', 'Motorcycle updated.');
    }

    public function destroy(Motorcycle $motorcycle)
    {
        $this->authorize('delete', $motorcycle);

        if ($motorcycle->contracts()->whereIn('status', ['active', 'pending_enrolment'])->exists()) {
            return back()->with('error', 'Cannot delete a motorcycle with active contracts.');
        }

        $motorcycle->delete();
        return redirect()->route('motorcycles.index')->with('success', 'Motorcycle deleted.');
    }
}
