<?php
use App\Models\Project;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class AddHashedUrlColumnToProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->uuid('hashed_url');
        });
        $this->addUrls();
    }
    private function addUrls()
    {
        $projects = Project::all();
        foreach ($projects as $project) {
            $project->hashed_url = (string) Str::uuid();
            $project->save();
        }
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('hashed_url');
        });
    }
}
