<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Translation;
use App\Models\EventType;
use App\Models\WorkTimeType;
use App\Models\ProjectStatus;

class UpdateOldTranslations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       $event_types =  \DB::table('event_types')->select('name', 'id')->get();
       foreach($event_types as $et) {
            Translation::create([
                'table' => 'event_types',
                'model_id' => $et->id,
                'language_id' => 'pl',
                'field_name' => 'name',
                'localized_value' => $et->name
            ]);
        }

        $work_time_types =  \DB::table('work_time_types')->select('name', 'id')->get();
        foreach($work_time_types as $wt) {
            Translation::create([
                'table' => 'work_time_types',
                'model_id' => $wt->id,
                'language_id' => 'pl',
                'field_name' => 'name',
                'localized_value' => $wt->name
            ]);
        }
        $project_statuses = \DB::table('project_statuses')->select('name', 'id')->get();
        foreach($project_statuses as $st) {
            Translation::create([
                'table' => 'project_statuses',
                'model_id' => $st->id,
                'language_id' => 'pl',
                'field_name' => 'name',
                'localized_value' => $st->name
            ]);
        }
        Schema::table('project_statuses', function($table) {
           $table->dropColumn('name');
        });
        Schema::table('work_time_types', function($table) {
           $table->dropColumn('name');
        });
        Schema::table('event_types', function($table) {
           $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
