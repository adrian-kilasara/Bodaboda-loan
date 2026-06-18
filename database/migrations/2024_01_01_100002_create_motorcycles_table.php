<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('motorcycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->onDelete('restrict');
            $table->string('registration_number', 20)->unique();
            $table->string('make', 50);
            $table->string('model', 50);
            $table->smallInteger('manufacture_year')->nullable();
            $table->string('engine_number', 50)->nullable()->unique();
            $table->string('chassis_number', 50)->nullable()->unique();
            $table->string('color', 30)->nullable();
            $table->decimal('purchase_price', 12, 2);
            $table->date('purchase_date')->nullable();
            $table->enum('status', ['available', 'on_loan', 'repossessed', 'sold', 'maintenance'])->default('available');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('owner_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('motorcycles');
    }
};
