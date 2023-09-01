<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('game_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('memo_test_id');
            $table->unsignedBigInteger('retries')->default(0);
            $table->unsignedBigInteger('number_of_pairs')->default(0);
            $table->enum('state', ['Started', 'Completed'])->default('Started');
            $table->timestamps();
    
            $table->foreign('memo_test_id')->references('id')->on('memo_tests');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_sessions');
    }
};
