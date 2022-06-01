<?php

use App\Enums\ClientStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('nip', 10);
            $table->string('address', 255)->nullable();
            $table->string('contact_person')->nullable();
            $table->string('phone_number', 30)->nullable();
            $table->string('additional_informations', 1000)->nullable();
            $table->enum('status', ClientStatus::getValues())->default(ClientStatus::ACTIVE);
            $table->unsignedBigInteger('who_add')->nullable();
            $table->foreign('who_add')->references('id')->on('users');
            $table->softDeletes();
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
        Schema::dropIfExists('clients');
    }
}
