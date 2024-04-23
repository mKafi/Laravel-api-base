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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->tinyText('traderCode');
            $table->dateTime('sellingDate', $precision = 0);           
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('reference')->nullable();
            $table->string('phone');            
            $table->string('comment')->nullable();            
            $table->tinyText('salesPoint')->nullable();
            $table->tinyText('salesAgent')->nullable();
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
        Schema::dropIfExists('sales');
    }
};
