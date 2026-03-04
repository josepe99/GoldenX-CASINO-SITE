<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletTransactionsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id')->default(0)->index();
                $table->string('login')->nullable();
                $table->string('avatar')->nullable();
                $table->decimal('sum', 16, 2)->default(0);
                $table->string('data')->nullable();
                $table->string('transaction')->nullable()->index();
                $table->decimal('beforepay', 16, 2)->default(0);
                $table->decimal('afterpay', 16, 2)->default(0);
                $table->unsignedTinyInteger('status')->default(0)->index();
                $table->decimal('percent', 8, 2)->default(0);
                $table->string('img_system')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('payments', function (Blueprint $table) {
                if (!Schema::hasColumn('payments', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->default(0)->index();
                }
                if (!Schema::hasColumn('payments', 'login')) {
                    $table->string('login')->nullable();
                }
                if (!Schema::hasColumn('payments', 'avatar')) {
                    $table->string('avatar')->nullable();
                }
                if (!Schema::hasColumn('payments', 'sum')) {
                    $table->decimal('sum', 16, 2)->default(0);
                }
                if (!Schema::hasColumn('payments', 'data')) {
                    $table->string('data')->nullable();
                }
                if (!Schema::hasColumn('payments', 'transaction')) {
                    $table->string('transaction')->nullable();
                }
                if (!Schema::hasColumn('payments', 'beforepay')) {
                    $table->decimal('beforepay', 16, 2)->default(0);
                }
                if (!Schema::hasColumn('payments', 'afterpay')) {
                    $table->decimal('afterpay', 16, 2)->default(0);
                }
                if (!Schema::hasColumn('payments', 'status')) {
                    $table->unsignedTinyInteger('status')->default(0)->index();
                }
                if (!Schema::hasColumn('payments', 'percent')) {
                    $table->decimal('percent', 8, 2)->default(0);
                }
                if (!Schema::hasColumn('payments', 'img_system')) {
                    $table->string('img_system')->nullable();
                }
                if (!Schema::hasColumn('payments', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (!Schema::hasColumn('payments', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
            });
        }

        if (!Schema::hasTable('withdraws')) {
            Schema::create('withdraws', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id')->default(0)->index();
                $table->string('login')->nullable();
                $table->string('avatar')->nullable();
                $table->string('ps')->nullable();
                $table->string('wallet')->nullable();
                $table->unsignedTinyInteger('mult')->default(0);
                $table->decimal('sum', 16, 2)->default(0);
                $table->decimal('sum_full', 16, 2)->default(0);
                $table->string('date')->nullable();
                $table->unsignedTinyInteger('status')->default(0)->index();
                $table->string('img_system')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table('withdraws', function (Blueprint $table) {
                if (!Schema::hasColumn('withdraws', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->default(0)->index();
                }
                if (!Schema::hasColumn('withdraws', 'login')) {
                    $table->string('login')->nullable();
                }
                if (!Schema::hasColumn('withdraws', 'avatar')) {
                    $table->string('avatar')->nullable();
                }
                if (!Schema::hasColumn('withdraws', 'ps')) {
                    $table->string('ps')->nullable();
                }
                if (!Schema::hasColumn('withdraws', 'wallet')) {
                    $table->string('wallet')->nullable();
                }
                if (!Schema::hasColumn('withdraws', 'mult')) {
                    $table->unsignedTinyInteger('mult')->default(0);
                }
                if (!Schema::hasColumn('withdraws', 'sum')) {
                    $table->decimal('sum', 16, 2)->default(0);
                }
                if (!Schema::hasColumn('withdraws', 'sum_full')) {
                    $table->decimal('sum_full', 16, 2)->default(0);
                }
                if (!Schema::hasColumn('withdraws', 'date')) {
                    $table->string('date')->nullable();
                }
                if (!Schema::hasColumn('withdraws', 'status')) {
                    $table->unsignedTinyInteger('status')->default(0)->index();
                }
                if (!Schema::hasColumn('withdraws', 'img_system')) {
                    $table->string('img_system')->nullable();
                }
                if (!Schema::hasColumn('withdraws', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (!Schema::hasColumn('withdraws', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
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
        // Compatibility migration. No destructive rollback.
    }
}
