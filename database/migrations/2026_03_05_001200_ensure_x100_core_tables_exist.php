<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnsureX100CoreTablesExist extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('x100'))
        {
            Schema::create('x100', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id')->index();
                $table->unsignedInteger('coff')->index();
                $table->string('img')->nullable();
                $table->string('login')->nullable();
                $table->decimal('bet', 16, 2)->default(0);
                $table->unsignedBigInteger('round_id')->nullable()->index();
                $table->unsignedTinyInteger('settled')->default(0)->index();
                $table->decimal('payout', 16, 2)->default(0);
                $table->timestamps();
            });
        }
        else
        {
            if (!Schema::hasColumn('x100', 'user_id'))
            {
                Schema::table('x100', function (Blueprint $table) {
                    $table->unsignedBigInteger('user_id')->nullable()->index();
                });
            }
            if (!Schema::hasColumn('x100', 'coff'))
            {
                Schema::table('x100', function (Blueprint $table) {
                    $table->unsignedInteger('coff')->nullable()->index();
                });
            }
            if (!Schema::hasColumn('x100', 'img'))
            {
                Schema::table('x100', function (Blueprint $table) {
                    $table->string('img')->nullable();
                });
            }
            if (!Schema::hasColumn('x100', 'login'))
            {
                Schema::table('x100', function (Blueprint $table) {
                    $table->string('login')->nullable();
                });
            }
            if (!Schema::hasColumn('x100', 'bet'))
            {
                Schema::table('x100', function (Blueprint $table) {
                    $table->decimal('bet', 16, 2)->default(0);
                });
            }
            if (!Schema::hasColumn('x100', 'round_id'))
            {
                Schema::table('x100', function (Blueprint $table) {
                    $table->unsignedBigInteger('round_id')->nullable()->index();
                });
            }
            if (!Schema::hasColumn('x100', 'settled'))
            {
                Schema::table('x100', function (Blueprint $table) {
                    $table->unsignedTinyInteger('settled')->default(0)->index();
                });
            }
            if (!Schema::hasColumn('x100', 'payout'))
            {
                Schema::table('x100', function (Blueprint $table) {
                    $table->decimal('payout', 16, 2)->default(0);
                });
            }
            if (!Schema::hasColumn('x100', 'created_at'))
            {
                Schema::table('x100', function (Blueprint $table) {
                    $table->timestamp('created_at')->nullable();
                });
            }
            if (!Schema::hasColumn('x100', 'updated_at'))
            {
                Schema::table('x100', function (Blueprint $table) {
                    $table->timestamp('updated_at')->nullable();
                });
            }
        }

        if (!Schema::hasTable('x100_history'))
        {
            Schema::create('x100_history', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedInteger('number')->nullable();
                $table->unsignedInteger('coff')->nullable();
                $table->longText('random')->nullable();
                $table->longText('signature')->nullable();
                $table->timestamps();
            });
        }
        else
        {
            if (!Schema::hasColumn('x100_history', 'number'))
            {
                Schema::table('x100_history', function (Blueprint $table) {
                    $table->unsignedInteger('number')->nullable();
                });
            }
            if (!Schema::hasColumn('x100_history', 'coff'))
            {
                Schema::table('x100_history', function (Blueprint $table) {
                    $table->unsignedInteger('coff')->nullable();
                });
            }
            if (!Schema::hasColumn('x100_history', 'random'))
            {
                Schema::table('x100_history', function (Blueprint $table) {
                    $table->longText('random')->nullable();
                });
            }
            if (!Schema::hasColumn('x100_history', 'signature'))
            {
                Schema::table('x100_history', function (Blueprint $table) {
                    $table->longText('signature')->nullable();
                });
            }
            if (!Schema::hasColumn('x100_history', 'created_at'))
            {
                Schema::table('x100_history', function (Blueprint $table) {
                    $table->timestamp('created_at')->nullable();
                });
            }
            if (!Schema::hasColumn('x100_history', 'updated_at'))
            {
                Schema::table('x100_history', function (Blueprint $table) {
                    $table->timestamp('updated_at')->nullable();
                });
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('x100_history'))
        {
            Schema::dropIfExists('x100_history');
        }

        if (Schema::hasTable('x100'))
        {
            Schema::dropIfExists('x100');
        }
    }
}
