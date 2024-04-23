<?php
use App\Models\Api\Sales;
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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();            
            $table->unsignedBigInteger('saleId');
            $table->foreign('saleId')->references('id')->on('sales');            
            $table->string('invoiceCode');            
            $table->float('subTotal', 8, 2);
            $table->float('tax', 4, 2);
            $table->float('previousDue', 8, 2);            
            $table->float('discount', 8, 2);
            $table->float('grandTotal', 8, 2);            
            $table->float('paid', 8, 2);
            $table->float('due', 8, 2);
            $table->dateTime('nextPaymentDate', $precision = 0)->nullable();            
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
        Schema::dropIfExists('invoices');
    }
};
