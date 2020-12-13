<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            //chave estrangeira
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            //total da compra
            $table->double('total', 10,2);
            //qual a situação da ordem
            $table->enum('status', ['started', 'approved', 'canceled']);
            //id q o paypal passa para identificaar atransação
            $table->string('payment_id');
            //indentificar a transação
            $table->string('identify');
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
        Schema::dropIfExists('orders');
    }
}
