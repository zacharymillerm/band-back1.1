<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration
{
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('tag1')->nullable();
            $table->string('tag2')->nullable();
            $table->string('tag3')->nullable();
            $table->string('tag4')->nullable();
            $table->string('tag5')->nullable();
            $table->string('tag6')->nullable();
            $table->string('tag7')->nullable();
            $table->string('tag8')->nullable();
            $table->text('competencies')->nullable(); // Use json type for competencies
            $table->string('links')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('teams');
    }
}
