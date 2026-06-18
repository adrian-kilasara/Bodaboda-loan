<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContractRequest;
use App\Models\Contract;
use App\Models\Motorcycle;
use App\Services\ScheduleGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContractController extends Controller
{
    public function __construct(private ScheduleGenerator $scheduler) {}

    public function index()
    {
        $contracts = Contract::where('owner_id', auth()->id())
            ->with(['motorcycle', 'driver'])
            ->latest()
            ->paginate(15);

        return view('contracts.index', compact('contracts'));
    }

    public function create()
    {
        $motorcycles = Motorcycle::where('owner_id', auth()->id())
            ->where('status', 'available')
            ->get();

        return view('contracts.create', compact('motorcycles'));
    }

    public function store(StoreContractRequest $request)
    {
        $data = $request->validated();
        $motorcycle = Motorcycle::findOrFail($data['motorcycle_id']);
        $this->authorize('create', Contract::class);

        if ($motorcycle->owner_id !== auth()->id()) {
            abort(403);
        }

        $principal   = (string)($data['principal_amount']);
        $markup      = (string)($data['markup_amount'] ?? '0');
        $downPayment = (string)($data['down_payment'] ?? '0');
        $totalPayable = bcadd($principal, $markup, 2);
        $financed     = bcsub($totalPayable, $downPayment, 2);

        $contract = Contract::create([
            'contract_number'        => 'BL-' . str_pad(Contract::withTrashed()->count() + 1, 6, '0', STR_PAD_LEFT),
            'motorcycle_id'          => $data['motorcycle_id'],
            'owner_id'               => auth()->id(),
            'principal_amount'       => $principal,
            'markup_amount'          => $markup,
            'total_payable'          => $totalPayable,
            'down_payment'           => $downPayment,
            'financed_amount'        => $financed,
            'installment_amount'     => $data['installment_amount'],
            'installment_frequency'  => $data['installment_frequency'],
            'number_of_installments' => $data['number_of_installments'],
            'penalty_type'           => $data['penalty_type'],
            'penalty_amount'         => $data['penalty_amount'] ?? 0,
            'grace_period_days'      => $data['grace_period_days'] ?? 0,
            'start_date'             => $data['start_date'],
            'status'                 => 'pending_enrolment',
            'notes'                  => $data['notes'] ?? null,
        ]);

        $this->scheduler->generate($contract);
        $motorcycle->update(['status' => 'on_loan']);

        return redirect()->route('contracts.show', $contract)
            ->with('success', 'Contract created. Share the enrolment key with the driver.');
    }

    public function show(Contract $contract)
    {
        $this->authorize('view', $contract);
        $contract->load(['motorcycle', 'driver', 'installments', 'payments.recorder', 'contacts']);
        return view('contracts.show', compact('contract'));
    }

    public function generateKey(Contract $contract)
    {
        $this->authorize('generateKey', $contract);

        $plainKey = Str::upper(Str::random(8));
        $contract->update([
            'enrolment_key'            => bcrypt($plainKey),
            'enrolment_key_expires_at' => now()->addDays(7),
        ]);

        return back()->with('enrolment_key', $plainKey)
            ->with('success', 'Enrolment key generated. Copy it now — it will not be shown again.');
    }
}
