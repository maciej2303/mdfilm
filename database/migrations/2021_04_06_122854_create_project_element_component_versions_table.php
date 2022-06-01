<?php

use App\Enums\ProjectVersionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectElementComponentVersionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_element_component_versions', function (Blueprint $table) {
            $table->id();
            $table->string('version', 50);
            $table->unsignedBigInteger('project_element_component_id');
            $table->foreign('project_element_component_id', 'fk_component_version')->references('id')->on('project_element_components')->onDelete('cascade');
            $table->enum('status', ProjectVersionStatus::getValues())->default(ProjectVersionStatus::IN_PROGRESS);
            $table->boolean('inner')->default(0);
            $table->boolean('active')->default(1);
            $table->string('pdf', 300)->nullable();
            $table->string('youtube_url', 300)->nullable();
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
        Schema::dropIfExists('project_element_component_versions');
    }
}
