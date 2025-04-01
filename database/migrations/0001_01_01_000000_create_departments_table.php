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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // اسم القسم
            $table->string('code')->unique();  // رمز القسم
            $table->text('description')->nullable();  // وصف القسم
            $table->string('location')->nullable();  // موقع القسم في المستشفى
            $table->string('head_of_department')->nullable();  // رئيس القسم
            $table->integer('capacity')->nullable();  // السعة الاستيعابية للقسم
            $table->boolean('is_active')->default(true);  // حالة القسم (نشط/غير نشط)
            $table->timestamps();  // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
