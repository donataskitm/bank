<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id_from');
            $table->unsignedBigInteger('account_id_to');
            $table->text('purpose');
            $table->integer('status');
            $table->decimal('amount',9,2);
            $table->date('date')->format('Y-m-d');
            $table->timestamps();
            $table->foreign('account_id_from')
                ->references('id')
                ->on('accounts');
            $table->foreign('account_id_to')
                ->references('id')
                ->on('accounts');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transfers');
    }
}
