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
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('productId');
            $table->foreign('productId')->references('id')->on('products');
            $table->float('wholesellPrice',8,2);
            $table->float('retailPrice',8,2);
            $table->float('specialPrice',8,2);
            $table->float('lotUnit',8,2);
            $table->float('lotPrice',8,2);
            $table->string('unitName',8,2);
            $table->string('lotNumber');
            $table->text('meta')->nullable();
            $table->tinyText('status')->nullable();
            $table->smallInteger('flag');
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
        Schema::dropIfExists('product_stocks');
    }
};
