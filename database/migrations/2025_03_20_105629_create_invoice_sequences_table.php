<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('invoice_sequences', function (Blueprint $table) {
            $table->id();
            $table->string('last_invoice_number')->default('INV-100'); // Default starting number
            $table->timestamps();
        });

        // Insert the initial value
        DB::table('invoice_sequences')->insert([
            'last_invoice_number' => 'INV-100',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('invoice_sequences');
    }
};