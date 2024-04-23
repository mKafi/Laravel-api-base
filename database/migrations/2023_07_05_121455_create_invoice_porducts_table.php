<?php
use App\Models\Api\Invoice;
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
        Schema::create('invoice_porducts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoiceId');
            $table->foreign('invoiceId')->references('id')->on('invoices'); 
            $table->integer('productId');
            $table->string('itemTitle');
            $table->string('itemModel')->nullable();
            $table->integer('qty');
            $table->float('unitPrice', 8, 2);
            $table->float('itemTax', 2, 2);
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
        Schema::dropIfExists('invoice_porducts');
    }
};
