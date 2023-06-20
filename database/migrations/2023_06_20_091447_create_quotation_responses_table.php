<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quotation_id');
            $table->foreign('quotation_id')->references('id')->on('quotations')->onDelete('cascade');
            $table->unsignedBigInteger('vendor_id');
            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
            $table->decimal('offered_price', 8, 2);
            $table->text('description');
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
        Schema::dropIfExists('quotation_responses');
    }
}
