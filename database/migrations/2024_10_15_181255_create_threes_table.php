<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThreesTable extends Migration
{
    public function up()
    {
        Schema::create('threes', function (Blueprint $table) {
            $table->id();
            $table->string('title1')->nullable();
            $table->text('content1')->nullable();
            $table->string('title2')->nullable();
            $table->text('content2')->nullable();
            $table->string('video')->nullable();
            $table->string('links')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('threes');
    }
}