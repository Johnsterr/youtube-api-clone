<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Channel;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            //$table->foreignId('channel_id')->constrained();

            // Две строки (старая нотация) эквивалентны одной
            //$table->unsignedBigInteger('channel_id');
            //$table->foreign('channel_id')->references('id')->on('channels');
            // Новая нотация
            $table->foreignIdFor(Channel::class)->constrained();

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
        Schema::dropIfExists('videos');
    }
};
