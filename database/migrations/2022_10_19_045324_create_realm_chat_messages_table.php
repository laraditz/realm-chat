<?php

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
        Schema::create('realm_chat_messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('message_id', 50);
            $table->string('device_id', 50);
            $table->string('phone_number', 100);
            $table->text('body')->nullable();
            $table->string('media_url')->nullable();
            $table->smallInteger('direction')->nullable();
            $table->smallInteger('type')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index('message_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('realm_chat_messages');
    }
};
