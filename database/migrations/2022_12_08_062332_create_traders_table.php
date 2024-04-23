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
        Schema::create('traders', function (Blueprint $table) {
            $table->id(); 
            $table->tinyText('traderCode');
            $table->string('title');
            $table->text('shortDescription');
            $table->string('category');
            $table->string('tags');
            $table->string('owner');
            $table->string('ownerContact');
            $table->string('publicAddress');
            $table->string('tradeLicense');            
            $table->text('logoUrl');
            $table->tinyText('status');
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
        Schema::dropIfExists('traders');
    }
};
