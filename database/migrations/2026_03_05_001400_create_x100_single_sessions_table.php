<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateX100SingleSessionsTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('x100_single_sessions'))
        {
            return;
        }

        Schema::create('x100_single_sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedInteger('coff')->default(2);
            $table->decimal('base_bet', 16, 2)->default(0);
            $table->decimal('current_amount', 16, 2)->default(0);
            $table->string('status', 16)->default('ACTIVE')->index();
            $table->unsignedTinyInteger('balance_type')->default(0);
            $table->unsignedInteger('rounds_played')->default(0);
            $table->unsignedInteger('last_result_coff')->nullable();
            $table->unsignedInteger('last_number')->nullable();
            $table->longText('last_random')->nullable();
            $table->longText('last_signature')->nullable();
            $table->decimal('cashed_amount', 16, 2)->default(0);
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        if (Schema::hasTable('x100_single_sessions'))
        {
            Schema::dropIfExists('x100_single_sessions');
        }
    }
}

