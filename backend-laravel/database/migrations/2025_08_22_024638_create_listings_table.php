<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('listings', function (Blueprint $table) {
        $table->id();
        $table->enum('type', ['sale', 'rent'])->default('sale');
        $table->tinyInteger('bedrooms')->unsigned();
        $table->tinyInteger('bathrooms')->unsigned();
        $table->boolean('parking')->default(false);
        $table->boolean('furnished')->default(false);
        $table->text('address');
        $table->text('description');
        $table->boolean('offer')->default(false);
        $table->decimal('regularPrice', 12, 2);
        $table->decimal('discountedPrice', 12, 2)->nullable();
        $table->decimal('latitude', 10, 8);
        $table->decimal('longitude', 11, 8);
        $table->decimal('geolocation_lat', 10, 8)->nullable();
        $table->decimal('geolocation_lng', 11, 8)->nullable();
        $table->timestamp('timestamp')->useCurrent();
        $table->string('userRef');
        $table->tinyInteger('status')->default(1);
        $table->integer('created_by')->unsigned()->nullable();
        $table->integer('updated_by')->unsigned()->nullable();
        $table->integer('deleted_by')->unsigned()->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
