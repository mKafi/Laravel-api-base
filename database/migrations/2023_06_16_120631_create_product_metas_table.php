<?php
use App\Models\Api\Trader;
use App\Models\Api\Product;
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
        Schema::create('product_metas', function (Blueprint $table) {
            $table->id();            
            $table->unsignedBigInteger('productId');
            $table->foreign('productId')->references('id')->on('products');
            $table->text('description')->nullable();
            $table->string('model')->nullable();
            $table->string('origin')->nullable();
            $table->string('company')->nullable();
            $table->string('variant')->nullable();
            $table->string('comment')->nullable();
            $table->text('otherMeta')->nullable();
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
        Schema::dropIfExists('product_metas');
    }
};
