<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddX100RoundsAndLedgerTables extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('x100_rounds')) {
            Schema::create('x100_rounds', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('status', 20)->default('WAITING')->index();
                $table->unsignedInteger('forced_coff')->nullable();
                $table->unsignedInteger('result_coff')->nullable();
                $table->timestamp('started_at')->nullable();
                $table->timestamp('betting_ends_at')->nullable();
                $table->timestamp('spinning_ends_at')->nullable();
                $table->timestamp('settled_at')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('x100')) {
            Schema::table('x100', function (Blueprint $table) {
                if (!Schema::hasColumn('x100', 'round_id')) {
                    $table->unsignedBigInteger('round_id')->nullable()->index();
                }
                if (!Schema::hasColumn('x100', 'settled')) {
                    $table->unsignedTinyInteger('settled')->default(0)->index();
                }
                if (!Schema::hasColumn('x100', 'payout')) {
                    $table->decimal('payout', 16, 2)->default(0);
                }
            });
        }

        if (!Schema::hasTable('x100_wallet_ledger')) {
            Schema::create('x100_wallet_ledger', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('round_id')->nullable()->index();
                $table->unsignedBigInteger('x100_id')->nullable()->index();
                $table->unsignedBigInteger('user_id')->index();
                $table->unsignedInteger('coff')->nullable();
                $table->string('entry_type', 10)->index();
                $table->decimal('amount', 16, 2);
                $table->decimal('balance_before', 16, 2)->default(0);
                $table->decimal('balance_after', 16, 2)->default(0);
                $table->text('meta')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('x100_wallet_ledger')) {
            Schema::dropIfExists('x100_wallet_ledger');
        }

        if (Schema::hasTable('x100')) {
            Schema::table('x100', function (Blueprint $table) {
                if (Schema::hasColumn('x100', 'payout')) {
                    $table->dropColumn('payout');
                }
                if (Schema::hasColumn('x100', 'settled')) {
                    $table->dropColumn('settled');
                }
                if (Schema::hasColumn('x100', 'round_id')) {
                    $table->dropColumn('round_id');
                }
            });
        }

        if (Schema::hasTable('x100_rounds')) {
            Schema::dropIfExists('x100_rounds');
        }
    }
}

