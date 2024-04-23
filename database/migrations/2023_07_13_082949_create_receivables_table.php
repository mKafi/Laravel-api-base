<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receivables', function (Blueprint $table) {
            $table->id();
            $table->tinyText('traderCode');
            $table->string('client', 50)->nullable();
            $table->text('address')->nullable();
            $table->string('reference', 100)->nullable();
            $table->bigInteger('contact');            
            $table->float('amount');
            $table->dateTime('dueDate')->nullable();
            $table->tinyText('type')->nullable();
            $table->text('comment')->nullable();
            $table->tinyText('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receivables');
    }
};
