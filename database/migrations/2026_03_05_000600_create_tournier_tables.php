<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTournierTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('tourniers')) {
            Schema::create('tourniers', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name')->nullable();
                $table->unsignedInteger('places')->default(0);
                $table->longText('prizes')->nullable();
                $table->unsignedBigInteger('start')->default(0);
                $table->unsignedBigInteger('end')->default(0);
                $table->string('class')->nullable();
                $table->string('game')->nullable();
                $table->unsignedTinyInteger('game_id')->default(0);
                $table->text('description')->nullable();
                $table->decimal('prize', 16, 2)->default(0);
                $table->unsignedTinyInteger('status')->default(0);
                $table->timestamps();

                $table->index(['game_id', 'status']);
            });
        } else {
            Schema::table('tourniers', function (Blueprint $table) {
                if (!Schema::hasColumn('tourniers', 'name')) {
                    $table->string('name')->nullable();
                }
                if (!Schema::hasColumn('tourniers', 'places')) {
                    $table->unsignedInteger('places')->default(0);
                }
                if (!Schema::hasColumn('tourniers', 'prizes')) {
                    $table->longText('prizes')->nullable();
                }
                if (!Schema::hasColumn('tourniers', 'start')) {
                    $table->unsignedBigInteger('start')->default(0);
                }
                if (!Schema::hasColumn('tourniers', 'end')) {
                    $table->unsignedBigInteger('end')->default(0);
                }
                if (!Schema::hasColumn('tourniers', 'class')) {
                    $table->string('class')->nullable();
                }
                if (!Schema::hasColumn('tourniers', 'game')) {
                    $table->string('game')->nullable();
                }
                if (!Schema::hasColumn('tourniers', 'game_id')) {
                    $table->unsignedTinyInteger('game_id')->default(0);
                }
                if (!Schema::hasColumn('tourniers', 'description')) {
                    $table->text('description')->nullable();
                }
                if (!Schema::hasColumn('tourniers', 'prize')) {
                    $table->decimal('prize', 16, 2)->default(0);
                }
                if (!Schema::hasColumn('tourniers', 'status')) {
                    $table->unsignedTinyInteger('status')->default(0);
                }
                if (!Schema::hasColumn('tourniers', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (!Schema::hasColumn('tourniers', 'updated_at')) {
                    $table->timestamp('updated_at')->nullable();
                }
            });
        }

        if (!Schema::hasTable('tournier_table')) {
            Schema::create('tournier_table', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('tournier_id')->default(0)->index();
                $table->unsignedBigInteger('user_id')->default(0)->index();
                $table->string('avatar')->nullable();
                $table->string('name')->nullable();
                $table->decimal('scores', 16, 2)->default(0);
                $table->timestamps();
            });
        } else {
            Schema::table('tournier_table', function (Blueprint $table) {
                if (!Schema::hasColumn('tournier_table', 'tournier_id')) {
                    $table->unsignedBigInteger('tournier_id')->default(0)->index();
                }
                if (!Schema::hasColumn('tournier_table', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->default(0)->index();
                }
                if (!Schema::hasColumn('tournier_table', 'avatar')) {
                    $table->string('avatar')->nullable();
                }
                if (!Schema::hasColumn('tournier_table', 'name')) {
                    $table->string('name')->nullable();
                }
                if (!Schema::hasColumn('tournier_table', 'scores')) {
                    $table->decimal('scores', 16, 2)->default(0);
                }
                if (!Schema::hasColumn('tournier_table', 'created_at')) {
                    $table->timestamp('created_at')->nullable();
                }
                if (!Schema::hasColumn('tournier_table', 'updated_at')) {
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

