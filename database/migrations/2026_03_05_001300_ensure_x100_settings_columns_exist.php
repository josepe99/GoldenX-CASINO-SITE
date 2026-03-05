<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnsureX100SettingsColumnsExist extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('settings'))
        {
            return;
        }

        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'wheel_bank'))
            {
                $table->decimal('wheel_bank', 16, 2)->default(0);
            }
            if (!Schema::hasColumn('settings', 'wheel_profit'))
            {
                $table->decimal('wheel_profit', 16, 2)->default(0);
            }
            if (!Schema::hasColumn('settings', 'youtube'))
            {
                $table->unsignedTinyInteger('youtube')->default(0);
            }
            if (!Schema::hasColumn('settings', 'status_x100'))
            {
                $table->unsignedTinyInteger('status_x100')->default(0);
            }
            if (!Schema::hasColumn('settings', 'win_x100'))
            {
                $table->string('win_x100', 32)->default('false');
            }
            if (!Schema::hasColumn('settings', 'x100WinNumber'))
            {
                $table->unsignedInteger('x100WinNumber')->default(0);
            }
            if (!Schema::hasColumn('settings', 'auto_x100'))
            {
                $table->unsignedTinyInteger('auto_x100')->default(1);
            }
            if (!Schema::hasColumn('settings', 'coeff_bonus'))
            {
                $table->string('coeff_bonus', 32)->default('false');
            }
            if (!Schema::hasColumn('settings', 'X100BonusUser_ID'))
            {
                $table->unsignedBigInteger('X100BonusUser_ID')->default(0);
            }
            if (!Schema::hasColumn('settings', 'X100BonusAvatar'))
            {
                $table->string('X100BonusAvatar')->nullable();
            }
        });
    }

    public function down()
    {
        // Compatibility migration: keep columns to avoid breaking legacy installs.
    }
}

