<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->text('address');
            $table->timestamps();
        });

        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_number')->unique();
            $table->string('title');
            $table->text('description');
            $table->date('incident_date');
            $table->enum('status', ['Open', 'Pending', 'Closed'])->default('Open');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('lawyer_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('hearings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained()->onDelete('cascade');
            $table->dateTime('hearing_date');
            $table->string('location');
            $table->text('notes')->nullable();
            $table->string('status')->default('Scheduled');
            $table->timestamps();
        });

        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('file_path');
            $table->string('file_type');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['Admin', 'Lawyer', 'Client'])->default('Lawyer');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
        Schema::dropIfExists('hearings');
        Schema::dropIfExists('cases');
        Schema::dropIfExists('clients');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
