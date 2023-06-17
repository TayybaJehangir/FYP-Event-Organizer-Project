<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->string('business_name');
            $table->string('business_type');
            $table->string('business_mobile_number');
            $table->text('business_address');
            $table->text('business_details');
            $table->integer('people_capacity_min');
            $table->integer('people_capacity_max');
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('price_per_person', 8, 2);
            $table->text('services_and_amenities');
            $table->string('cover_photo')->nullable();
            $table->text('images')->nullable();
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('businesses');
    }
}
