<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeatureGroupUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feature_group_user', function (Blueprint $table) {
            $table->unsignedBigInteger('feature_group_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('feature_group_id')->references('id')->on('feature_groups')->onDelete('CASCADE');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');

            $table->primary(['feature_group_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feature_group_user');
    }
}
