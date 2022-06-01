<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOnDeleteCascadeInEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign('events_project_id_foreign');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->dropForeign('events_event_type_id_foreign');
            $table->foreign('event_type_id')->references('id')->on('event_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign('events_project_id_foreign');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->dropForeign('events_event_type_id_foreign');
            $table->foreign('event_type_id')->references('id')->on('event_types');
        });
    }
}
