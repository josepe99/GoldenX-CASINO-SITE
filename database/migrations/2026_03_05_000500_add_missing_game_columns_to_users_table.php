<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingGameColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'count_win')) {
                $table->unsignedInteger('count_win')->default(0)->after('lose_games');
            }

            if (!Schema::hasColumn('users', 'minesStart')) {
                $table->unsignedTinyInteger('minesStart')->default(0)->after('count_win');
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
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'minesStart')) {
                $table->dropColumn('minesStart');
            }

            if (Schema::hasColumn('users', 'count_win')) {
                $table->dropColumn('count_win');
            }
        });
    }
}
