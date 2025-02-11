<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactoryShowsTable extends Migration
{
    public function up()
    {
        Schema::create('factory_shows', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('video')->nullable();
            $table->integer('queue')->default(0);
            $table->text('description')->nullable();
            $table->string('links')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('factory_shows');
    }
}
