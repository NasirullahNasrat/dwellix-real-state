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
    Schema::create('listingimages', function (Blueprint $table) {
        $table->id();
        $table->foreignId('listing_id')->constrained('listings')->onDelete('cascade');
        $table->text('image_url');
        $table->boolean('is_cover')->default(false);
        $table->integer('upload_order');
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
        Schema::dropIfExists('listing_images');
    }
};
