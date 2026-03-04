<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExtendSystemPaymentTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('system_dep')) {
            Schema::create('system_dep', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->nullable();
                $table->decimal('min_sum', 16, 2)->default(0);
                $table->decimal('comm_percent', 8, 2)->default(0);
                $table->string('img')->nullable();
                $table->string('color', 32)->nullable();
                $table->unsignedTinyInteger('ps')->default(0);
                $table->string('number_ps')->nullable();
                $table->unsignedTinyInteger('off')->default(0);
                $table->integer('sort')->default(0);
            });
        } else {
            Schema::table('system_dep', function (Blueprint $table) {
                if (!Schema::hasColumn('system_dep', 'name')) {
                    $table->string('name')->nullable();
                }
                if (!Schema::hasColumn('system_dep', 'min_sum')) {
                    $table->decimal('min_sum', 16, 2)->default(0);
                }
                if (!Schema::hasColumn('system_dep', 'comm_percent')) {
                    $table->decimal('comm_percent', 8, 2)->default(0);
                }
                if (!Schema::hasColumn('system_dep', 'img')) {
                    $table->string('img')->nullable();
                }
                if (!Schema::hasColumn('system_dep', 'color')) {
                    $table->string('color', 32)->nullable();
                }
                if (!Schema::hasColumn('system_dep', 'ps')) {
                    $table->unsignedTinyInteger('ps')->default(0);
                }
                if (!Schema::hasColumn('system_dep', 'number_ps')) {
                    $table->string('number_ps')->nullable();
                }
                if (!Schema::hasColumn('system_dep', 'sort')) {
                    $table->integer('sort')->default(0);
                }
            });
        }

        if (!Schema::hasTable('system_withdraw')) {
            Schema::create('system_withdraw', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->nullable();
                $table->decimal('min_sum', 16, 2)->default(0);
                $table->decimal('comm_percent', 8, 2)->default(0);
                $table->decimal('comm_rub', 16, 2)->default(0);
                $table->string('img')->nullable();
                $table->string('color', 32)->nullable();
                $table->unsignedTinyInteger('off')->default(0);
            });
        } else {
            Schema::table('system_withdraw', function (Blueprint $table) {
                if (!Schema::hasColumn('system_withdraw', 'name')) {
                    $table->string('name')->nullable();
                }
                if (!Schema::hasColumn('system_withdraw', 'min_sum')) {
                    $table->decimal('min_sum', 16, 2)->default(0);
                }
                if (!Schema::hasColumn('system_withdraw', 'comm_percent')) {
                    $table->decimal('comm_percent', 8, 2)->default(0);
                }
                if (!Schema::hasColumn('system_withdraw', 'comm_rub')) {
                    $table->decimal('comm_rub', 16, 2)->default(0);
                }
                if (!Schema::hasColumn('system_withdraw', 'img')) {
                    $table->string('img')->nullable();
                }
                if (!Schema::hasColumn('system_withdraw', 'color')) {
                    $table->string('color', 32)->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No destructive rollback for compatibility migration.
    }
}
