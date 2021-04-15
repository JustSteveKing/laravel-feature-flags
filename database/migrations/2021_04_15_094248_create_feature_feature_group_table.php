<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeatureFeatureGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feature_feature_group', function (Blueprint $table) {
            $table->unsignedBigInteger('feature_id');
            $table->unsignedBigInteger('feature_group_id');

            $table->foreign('feature_id')->references('id')->on('features')->onDelete('CASCADE');
            $table->foreign('feature_group_id')->references('id')->on('feature_groups')->onDelete('CASCADE');

            $table->primary(['feature_id', 'feature_group_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feature_feature_group');
    }
}
