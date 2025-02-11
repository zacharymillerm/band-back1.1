<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSiteTagsToSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->json('siteTags')->nullable(); // Add siteTags column as JSON
        });
    }

    public function down()
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn('siteTags'); // Rollback the addition
        });
    }
}
