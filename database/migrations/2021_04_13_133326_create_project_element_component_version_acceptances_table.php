<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectElementComponentVersionAcceptancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_element_component_version_acceptances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_element_component_version_id');
            $table->foreign('project_element_component_version_id', 'fk_project_component_version')->references('id')->on('project_element_component_versions')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('acceptance')->default(0);
            $table->datetime('acceptance_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_element_component_version_acceptances');
    }
}
