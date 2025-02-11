<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentalsTable extends Migration
{
    public function up()
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->string('cost'); // 'require' is not used in Laravel; use validation instead
            $table->text('files')->nullable(); // Use json type for files to store array of file names
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rentals');
    }
}
