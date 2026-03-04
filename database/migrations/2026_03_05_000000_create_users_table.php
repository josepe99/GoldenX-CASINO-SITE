<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('users')) {
            return;
        }

        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('email')->nullable()->unique();
            $table->string('password')->nullable();

            $table->string('avatar')->nullable();
            $table->string('ip')->nullable();
            $table->string('social')->nullable();
            $table->text('videocard')->nullable();
            $table->string('vk_id')->nullable();
            $table->string('why_ban')->nullable();

            $table->unsignedTinyInteger('admin')->default(0);
            $table->unsignedTinyInteger('ban')->default(0);
            $table->unsignedTinyInteger('status')->default(0);
            $table->unsignedTinyInteger('type_balance')->default(0);
            $table->unsignedTinyInteger('newYear')->default(0);
            $table->unsignedTinyInteger('bonus_up')->default(0);
            $table->unsignedTinyInteger('bonus_1')->default(0);
            $table->unsignedTinyInteger('bonus_2')->default(0);
            $table->unsignedTinyInteger('reposts')->default(0);
            $table->unsignedTinyInteger('chat_ban')->default(0);

            $table->unsignedInteger('refs')->default(0);
            $table->unsignedBigInteger('ref_id')->default(0);
            $table->decimal('ref_coeff', 8, 2)->default(0);
            $table->unsignedInteger('count_chat_ban')->default(0);
            $table->unsignedBigInteger('time_chat_ban')->default(0);
            $table->unsignedBigInteger('bdate')->default(0);

            $table->decimal('balance', 16, 2)->default(0);
            $table->decimal('demo_balance', 16, 2)->default(0);
            $table->decimal('deps', 16, 2)->default(0);
            $table->decimal('withdraws', 16, 2)->default(0);
            $table->decimal('profit', 16, 2)->default(0);
            $table->decimal('sum_bet', 16, 2)->default(0);
            $table->decimal('sum_win', 16, 2)->default(0);
            $table->decimal('max_win', 16, 2)->default(0);
            $table->decimal('balance_ref', 16, 2)->default(0);
            $table->decimal('balance_repost', 16, 2)->default(0);
            $table->decimal('bonus_refs', 16, 2)->default(0);
            $table->decimal('sum_to_withdraw', 16, 2)->default(0);
            $table->decimal('bonusCoin', 16, 2)->default(0);
            $table->decimal('bonusMine', 16, 2)->default(0);

            $table->unsignedInteger('win_games')->default(0);
            $table->unsignedInteger('lose_games')->default(0);

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('users')) {
            Schema::dropIfExists('users');
        }
    }
}
