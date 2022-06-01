<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeRelationColumnInTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('tasks')->truncate();

        Schema::table('tasks', function (Blueprint $table) {
            $table->morphs('relationable');
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign('fk_project__version');
            $table->dropColumn('project_element_component_version_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('project_element_component_version_id');
            $table->foreign('project_element_component_version_id', 'fk_project__version')->references('id')->on('project_element_component_versions')->onDelete('cascade');
            $table->dropcolumn('relationable_id');
            $table->dropcolumn('relationable_type');
        });
    }
}
