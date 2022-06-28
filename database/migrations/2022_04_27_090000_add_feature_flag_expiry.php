<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('features')) {
            Schema::table('features', function (Blueprint $table) {
                $table->datetime('expires_at')->nullable();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('features')) {
            Schema::table('features', function (Blueprint $table) {
                $table->dropColumn('expires_at');
            });
        }
    }
};
