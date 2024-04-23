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
        Schema::create('marchants', function (Blueprint $table) {
            $table->id();
            $table->tinyText('traderCode');
            $table->string('owner', 50)->nullable();
            $table->text('organization')->nullable();
            $table->text('description')->nullable();
            $table->string('contact', 100)->nullable();
            $table->text('comment');
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
        Schema::dropIfExists('marchants');
    }
};
