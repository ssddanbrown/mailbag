<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSendRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('send_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->index();
            $table->foreignId('send_id')->index();
            $table->timestamp('sent_at')->nullable();
            $table->string('key', 100)->index();
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
        Schema::dropIfExists('send_records');
    }
}
