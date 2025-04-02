<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained('appointments');
            $table->foreignId('patient_id')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('amount', 10, 2);  // المبلغ الإجمالي
            $table->string('status')->default('pending');  // حالة الفاتورة (معلقة، مدفوعة، ملغاة)
            $table->text('description')->nullable();  // وصف الخدمات
            $table->date('due_date');  // تاريخ استحقاق الدفع
            $table->date('payment_date')->nullable();  // تاريخ الدفع الفعلي
            $table->string('payment_method')->nullable();  // طريقة الدفع
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
