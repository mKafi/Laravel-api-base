<?php
use App\Models\Api\Trader;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->tinyText('traderCode');
            $table->string('productCode')->nullable();
            $table->string('productTitle');            
            $table->float('wholesellPrice',8,2);
            $table->float('retailPrice',8,2);
            $table->float('specialPrice',8,2);
            $table->float('initialUnit',8,2);
            $table->string('lotNumber');
            $table->text('tags');            
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
        Schema::dropIfExists('products');
    }
};
