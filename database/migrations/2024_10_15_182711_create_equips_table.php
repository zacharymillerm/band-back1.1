<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipsTable extends Migration
{
    public function up()
    {
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('equipment_type')->nullable();
            $table->string('categoryType')->nullable();
            $table->string('brand')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('weight')->nullable();
            $table->string('series')->nullable();
            $table->string('file')->nullable();
            $table->integer('queue')->default(0);
            $table->text('description')->nullable();
            $table->text('dimension')->nullable(); 
            $table->text('blog_type')->nullable();
            $table->text('site_type')->nullable();
            $table->text('images')->nullable(); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('equipments');
    }
}
