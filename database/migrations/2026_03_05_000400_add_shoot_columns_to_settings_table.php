<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShootColumnsToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'shoot_bank')) {
                $table->decimal('shoot_bank', 16, 2)->default(0);
            }

            if (!Schema::hasColumn('settings', 'shoot_profit')) {
                $table->decimal('shoot_profit', 16, 2)->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'shoot_bank')) {
                $table->dropColumn('shoot_bank');
            }

            if (Schema::hasColumn('settings', 'shoot_profit')) {
                $table->dropColumn('shoot_profit');
            }
        });
    }
}

