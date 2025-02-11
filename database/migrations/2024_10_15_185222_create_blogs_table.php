<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('blog_type')->nullable();
            $table->string('startDate')->nullable();
            $table->string('endDate')->nullable();
            $table->string('guests')->nullable();
            $table->string('venue')->nullable();
            $table->string('video')->nullable();
            $table->text('images')->nullable();
            $table->text('tags')->nullable();
            $table->text('checkbox')->nullable();
            $table->text('equipment_type')->nullable();
            $table->text('site_type')->nullable();
            $table->json('cities')->nullable();
            $table->string('features')->nullable();
            $table->integer('queue')->default(0);
            $table->json('solution')->nullable();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('keyword')->nullable();
            $table->foreignId('site_id')->nullable()->constrained('sites')->onDelete('set null');
            $table->foreignId('three_id')->nullable()->constrained('threes')->onDelete('set null');
            $table->boolean('checked')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('blogs');
    }
};