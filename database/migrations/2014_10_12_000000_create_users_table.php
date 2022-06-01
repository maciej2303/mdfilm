<?php

use App\Enums\UserLevel;
use App\Enums\UserStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone_number', 30)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('level', UserLevel::getValues())->default(UserLevel::CLIENT);
            $table->datetime('last_login_at')->nullable();
            $table->enum('status', UserStatus::getValues())->default(UserStatus::ACTIVE);
            $table->rememberToken();
            $table->unsignedBigInteger('who_add')->nullable();
            $table->foreign('who_add')->references('id')->on('users');
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
        Schema::dropIfExists('users');
    }
}
