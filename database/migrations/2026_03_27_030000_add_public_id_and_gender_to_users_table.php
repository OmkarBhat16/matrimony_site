<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'public_id')) {
                $table->string('public_id')->nullable()->unique()->after('id');
            }

            if (! Schema::hasColumn('users', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('phone_number');
            }
        });

        Schema::create('user_id_counters', function (Blueprint $table) {
            $table->string('gender')->primary();
            $table->unsignedInteger('next_sequence')->default(0);
            $table->timestamps();
        });

        $genderByUserId = DB::table('user_profile')
            ->select('user_id', 'gender')
            ->get()
            ->keyBy('user_id');

        $users = DB::table('users')
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        $sequences = [
            'male' => 0,
            'female' => 0,
            'other' => 0,
        ];

        foreach ($users as $user) {
            $gender = $user->gender
                ?? ($genderByUserId[$user->id]->gender ?? null)
                ?? 'other';

            $gender = in_array($gender, ['male', 'female', 'other'], true) ? $gender : 'other';

            $sequences[$gender]++;
            $publicId = sprintf('%s-%05d', self::prefixForGender($gender), $sequences[$gender]);

            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'gender' => $gender,
                    'public_id' => $publicId,
                ]);
        }

        foreach ($sequences as $gender => $sequence) {
            DB::table('user_id_counters')->insert([
                'gender' => $gender,
                'next_sequence' => $sequence,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('user_id_counters');

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'public_id')) {
                $table->dropUnique('users_public_id_unique');
                $table->dropColumn('public_id');
            }

            if (Schema::hasColumn('users', 'gender')) {
                $table->dropColumn('gender');
            }
        });
    }

    private static function prefixForGender(string $gender): string
    {
        return match ($gender) {
            'female' => 'F',
            'male' => 'M',
            default => 'O',
        };
    }
};
