<?php

use App\Models\Attachment;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUserIdColumnToAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attachments', function (Blueprint $table) {
            $table->morphs('authorable');
            $table->string('label', 10)->nullable()->after('description');
        });

        $this->moveData();

        Schema::table('attachments', function (Blueprint $table) {
            $table->dropForeign('attachments_user_id_foreign');
            $table->dropColumn('user_id');
        });
    }

    private function moveData()
    {
        foreach (Attachment::all() as $attachment) {

            $attachment->authorable_id = $attachment->user_id;
            $attachment->authorable_type = User::class;
            $attachment->save();
        }
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attachments', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('relationable_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        foreach (Attachment::all() as $attachment) {
            $attachment->user_id = $attachment->authorable_id;
            $attachment->save();
        }

        Schema::table('attachments', function (Blueprint $table) {
            $table->dropcolumn('authorable_id');
            $table->dropcolumn('authorable_type');
            $table->dropColumn('label');
        });
    }
}
