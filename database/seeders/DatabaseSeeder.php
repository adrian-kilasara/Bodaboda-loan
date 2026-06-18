<?php

namespace Database\Seeders;

use App\Models\Contract;
use App\Models\DriverProfile;
use App\Models\Installment;
use App\Models\Motorcycle;
use App\Models\OwnerProfile;
use App\Models\Payment;
use App\Models\User;
use App\Services\PaymentAllocator;
use App\Services\ScheduleGenerator;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ──────────────────────────────────────────────
        User::create([
            'name'     => 'System Admin',
            'email'    => 'admin@bodaboda.test',
            'phone'    => '+255700000000',
            'role'     => 'admin',
            'status'   => 'active',
            'password' => Hash::make('password'),
        ]);

        // ── Owner ──────────────────────────────────────────────
        $owner = User::create([
            'name'     => 'Ahmed Salim',
            'email'    => 'owner@bodaboda.test',
            'phone'    => '+255711111111',
            'role'     => 'owner',
            'status'   => 'active',
            'password' => Hash::make('password'),
        ]);
        OwnerProfile::create(['user_id' => $owner->id, 'business_name' => 'Salim Boda Fleet']);

        // ── Driver ─────────────────────────────────────────────
        $driver = User::create([
            'name'     => 'John Mtemi',
            'email'    => 'driver@bodaboda.test',
            'phone'    => '+255722222222',
            'role'     => 'driver',
            'status'   => 'active',
            'password' => Hash::make('password'),
        ]);
        DriverProfile::create([
            'user_id'                => $driver->id,
            'driving_license_number' => 'TZ-DL-2021-4567',
            'national_id'            => 'TZ19900101-00001',
            'physical_address'       => 'Kariakoo, Dar es Salaam',
        ]);

        // ── Motorcycles ────────────────────────────────────────
        $bike1 = Motorcycle::create([
            'owner_id'            => $owner->id,
            'registration_number' => 'T 123 ABC',
            'make'                => 'Honda',
            'model'               => 'CG 125',
            'manufacture_year'    => 2021,
            'engine_number'       => 'ENG-0001',
            'chassis_number'      => 'CHS-0001',
            'color'               => 'Red',
            'purchase_price'      => 2500000,
            'purchase_date'       => '2022-01-15',
            'status'              => 'on_loan',
        ]);

        $bike2 = Motorcycle::create([
            'owner_id'            => $owner->id,
            'registration_number' => 'T 456 DEF',
            'make'                => 'Bajaj',
            'model'               => 'Boxer 150',
            'manufacture_year'    => 2022,
            'engine_number'       => 'ENG-0002',
            'chassis_number'      => 'CHS-0002',
            'color'               => 'Blue',
            'purchase_price'      => 2800000,
            'status'              => 'available',
        ]);

        // ── Contract (active, with driver + payments) ──────────
        $startDate = Carbon::now()->subMonths(3)->startOfMonth();
        $principal = '2500000';
        $markup    = '300000';
        $totalPayable = bcadd($principal, $markup, 2);
        $financed  = $totalPayable; // no down payment

        $contract = Contract::create([
            'contract_number'        => 'BL-000001',
            'motorcycle_id'          => $bike1->id,
            'owner_id'               => $owner->id,
            'driver_id'              => $driver->id,
            'principal_amount'       => $principal,
            'markup_amount'          => $markup,
            'total_payable'          => $totalPayable,
            'down_payment'           => '0',
            'financed_amount'        => $financed,
            'installment_amount'     => '80000',
            'installment_frequency'  => 'monthly',
            'number_of_installments' => 35,
            'penalty_type'           => 'none',
            'penalty_amount'         => '0',
            'grace_period_days'      => 2,
            'start_date'             => $startDate->toDateString(),
            'status'                 => 'active',
            'notes'                  => 'Demo contract. Honda CG 125 work-and-pay. TZS 80,000/month × 35 months.',
        ]);

        $scheduler = new ScheduleGenerator();
        $scheduler->generate($contract);

        // Record payments for the first 3 months (3 installments paid)
        $allocator = new PaymentAllocator();
        $channels = ['cash', 'mpesa', 'tigopesa'];

        for ($month = 0; $month < 3; $month++) {
            $payDate = $startDate->copy()->addMonths($month + 1)->subDays(2);
            $payment = Payment::create([
                'payment_reference'  => 'PAY-' . strtoupper(Str::random(8)),
                'contract_id'        => $contract->id,
                'driver_id'          => $driver->id,
                'amount'             => '80000',
                'payment_date'       => $payDate->toDateString(),
                'channel'            => $channels[$month % 3],
                'external_reference' => 'REF' . strtoupper(Str::random(6)),
                'recorded_by'        => $owner->id,
                'confirmed_by'       => $owner->id,
                'confirmed_at'       => $payDate,
                'status'             => 'confirmed',
            ]);
            $allocator->allocate($payment);
        }

        // ── Second owner with no contracts (for demo) ──────────
        $owner2 = User::create([
            'name'     => 'Fatuma Rashid',
            'email'    => 'owner2@bodaboda.test',
            'phone'    => '+255733333333',
            'role'     => 'owner',
            'status'   => 'active',
            'password' => Hash::make('password'),
        ]);
        OwnerProfile::create(['user_id' => $owner2->id, 'business_name' => 'Rashid Motors']);

        $this->command->info('✓ Demo data seeded.');
        $this->command->info('  Admin:  admin@bodaboda.test / password');
        $this->command->info('  Owner:  owner@bodaboda.test / password');
        $this->command->info('  Driver: driver@bodaboda.test / password');
    }
}
