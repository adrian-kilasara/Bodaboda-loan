<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number', 30)->unique();
            $table->foreignId('motorcycle_id')->constrained('motorcycles')->onDelete('restrict');
            $table->foreignId('owner_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('enrolment_key', 255)->nullable();
            $table->timestamp('enrolment_key_expires_at')->nullable();
            $table->decimal('principal_amount', 12, 2);
            $table->decimal('markup_amount', 12, 2)->default(0);
            $table->decimal('interest_rate', 5, 2)->nullable();
            $table->decimal('total_payable', 12, 2);
            $table->decimal('down_payment', 12, 2)->default(0);
            $table->decimal('financed_amount', 12, 2);
            $table->decimal('installment_amount', 12, 2);
            $table->enum('installment_frequency', ['daily', 'weekly', 'monthly']);
            $table->integer('number_of_installments');
            $table->enum('penalty_type', ['none', 'fixed', 'percentage'])->default('none');
            $table->decimal('penalty_amount', 12, 2)->default(0);
            $table->integer('grace_period_days')->default(0);
            $table->date('start_date');
            $table->date('expected_end_date')->nullable();
            $table->enum('status', ['draft', 'pending_enrolment', 'active', 'completed', 'defaulted', 'terminated'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('owner_id');
            $table->index('driver_id');
            $table->index('motorcycle_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
