<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateX100AntiTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('x100_anti'))
        {
            Schema::create('x100_anti', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedInteger('coeff')->unique();
                $table->decimal('win', 16, 2)->default(0);
                $table->timestamps();
            });
        }

        $coeffs = [2, 3, 10, 15, 20, 100];
        foreach ($coeffs as $coeff)
        {
            if (!DB::table('x100_anti')->where('coeff', $coeff)->exists())
            {
                $row = [
                    'coeff' => $coeff,
                    'win' => 0,
                ];

                if (Schema::hasColumn('x100_anti', 'created_at'))
                {
                    $row['created_at'] = now();
                }
                if (Schema::hasColumn('x100_anti', 'updated_at'))
                {
                    $row['updated_at'] = now();
                }

                DB::table('x100_anti')->insert($row);
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('x100_anti'))
        {
            Schema::dropIfExists('x100_anti');
        }
    }
}
