<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModelIdColumnToChangeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('change_logs')->truncate();

        Schema::table('change_logs', function (Blueprint $table) {
            $table->dropColumn('model_class');
            $table->nullableMorphs('model');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('change_logs', function (Blueprint $table) {
            $table->dropColumn('model_id');
            $table->dropColumn('model_type');
            $table->string('model_class')->nullable();
        });
    }
}
