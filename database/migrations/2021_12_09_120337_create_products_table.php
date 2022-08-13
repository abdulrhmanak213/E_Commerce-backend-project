<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
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
            $table->string ('name');
            $table->bigInteger ('price');
            $table->bigInteger ('current_price')->default(0);
            $table->bigInteger ('likes')->default(0);
            $table->Text('description');
            $table->date('exp_date');
            $table->text('img_url')->nullable();
            $table->string('phone');
            $table->integer('quantity')->default(1);
            $table->integer('view')->default(0);
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
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
}
