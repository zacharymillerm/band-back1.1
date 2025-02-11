<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('name'); // 'require' is not used in Laravel; use validation instead
            $table->string('file'); // 'require' is not used in Laravel; use validation instead
            $table->text('content'); // 'require' is not used in Laravel; use validation instead
            $table->text('displayType')->nullable(); // Use json type for displayType
            $table->unsignedInteger('queue')->default(0); // Set default value for queue
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
