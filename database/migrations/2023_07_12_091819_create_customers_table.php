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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();           
            $table->tinyText('traderCode');
            $table->string('name', 50)->nullable();
            $table->text('address')->nullable();
            $table->bigInteger('phone');
            $table->string('father', 50)->nullable();
            $table->string('mother', 50)->nullable();            
            $table->string('reference', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('nid',30)->nullable();
            $table->text('socialMeadiaUrl')->nullable();
            $table->text('profileInfo')->nullable();
            $table->text('photUrl')->nullable();            
            $table->string('tags')->nullable();
            $table->tinyText('status')->nullable();
            $table->tinyText('comment')->nullable();
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
        Schema::dropIfExists('customers');
    }
};
