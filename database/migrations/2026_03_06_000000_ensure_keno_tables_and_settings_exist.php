<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EnsureKenoTablesAndSettingsExist extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('keno'))
        {
            Schema::create('keno', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id')->default(0)->index();
                $table->decimal('bet', 16, 2)->default(0);
                $table->longText('numbers')->nullable();
                $table->string('login')->nullable();
                $table->string('img')->nullable();
                $table->decimal('win', 16, 2)->default(0);
                $table->timestamps();
            });
        }
        else
        {
            if (!Schema::hasColumn('keno', 'user_id'))
            {
                Schema::table('keno', function (Blueprint $table) {
                    $table->unsignedBigInteger('user_id')->default(0)->index();
                });
            }
            if (!Schema::hasColumn('keno', 'bet'))
            {
                Schema::table('keno', function (Blueprint $table) {
                    $table->decimal('bet', 16, 2)->default(0);
                });
            }
            if (!Schema::hasColumn('keno', 'numbers'))
            {
                Schema::table('keno', function (Blueprint $table) {
                    $table->longText('numbers')->nullable();
                });
            }
            if (!Schema::hasColumn('keno', 'login'))
            {
                Schema::table('keno', function (Blueprint $table) {
                    $table->string('login')->nullable();
                });
            }
            if (!Schema::hasColumn('keno', 'img'))
            {
                Schema::table('keno', function (Blueprint $table) {
                    $table->string('img')->nullable();
                });
            }
            if (!Schema::hasColumn('keno', 'win'))
            {
                Schema::table('keno', function (Blueprint $table) {
                    $table->decimal('win', 16, 2)->default(0);
                });
            }
            if (!Schema::hasColumn('keno', 'created_at'))
            {
                Schema::table('keno', function (Blueprint $table) {
                    $table->timestamp('created_at')->nullable();
                });
            }
            if (!Schema::hasColumn('keno', 'updated_at'))
            {
                Schema::table('keno', function (Blueprint $table) {
                    $table->timestamp('updated_at')->nullable();
                });
            }
        }

        if (!Schema::hasTable('settings'))
        {
            return;
        }

        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'status_keno'))
            {
                $table->unsignedTinyInteger('status_keno')->default(0);
            }
            if (!Schema::hasColumn('settings', 'keno_numbers'))
            {
                $table->longText('keno_numbers')->nullable();
            }
            if (!Schema::hasColumn('settings', 'youtube_keno'))
            {
                $table->unsignedTinyInteger('youtube_keno')->default(0);
            }
            if (!Schema::hasColumn('settings', 'numberBonusKeno'))
            {
                $table->unsignedInteger('numberBonusKeno')->default(0);
            }
            if (!Schema::hasColumn('settings', 'coeffBonusKeno'))
            {
                $table->unsignedInteger('coeffBonusKeno')->default(0);
            }
            if (!Schema::hasColumn('settings', 'noGetKeno'))
            {
                $table->longText('noGetKeno')->nullable();
            }
        });

        DB::table('settings')
            ->whereNull('keno_numbers')
            ->update(['keno_numbers' => '[]']);

        DB::table('settings')
            ->whereNull('noGetKeno')
            ->update(['noGetKeno' => '[]']);
    }

    public function down()
    {
        // Compatibility migration: keep legacy Keno schema in place.
    }
}
