<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateEventTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 300);
            $table->string('colour', 100);
            $table->integer('order')->default(1);
            $table->boolean('removable')->default(1);
            $table->timestamps();
        });

        DB::table('event_types')->insert(array(
            'name' => 'Termin zakończenia projektu',
            'colour' => '#c7ba2e',
            'order' => 1,
            'removable' => 0,
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event_types');
    }
}
