<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        DB::table('users')->select(['id', 'phone_number'])->orderBy('id')->chunkById(100, function ($users) {
            foreach ($users as $user) {
                $normalizedPhoneNumber = preg_replace('/\D+/', '', (string) $user->phone_number);

                if ($normalizedPhoneNumber !== $user->phone_number) {
                    DB::table('users')
                        ->where('id', $user->id)
                        ->update(['phone_number' => $normalizedPhoneNumber]);
                }
            }
        });

        $duplicates = DB::table('users')
            ->select('phone_number')
            ->groupBy('phone_number')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('phone_number');

        if ($duplicates->isNotEmpty()) {
            throw new RuntimeException(
                'Cannot enforce unique 10-digit phone numbers. Resolve duplicate values first: '.$duplicates->implode(', ')
            );
        }

        $invalidPhoneNumbers = DB::table('users')
            ->whereRaw(($driver === 'mysql' ? 'CHAR_LENGTH(phone_number)' : 'LENGTH(phone_number)').' != 10')
            ->pluck('phone_number');

        if ($invalidPhoneNumbers->isNotEmpty()) {
            throw new RuntimeException(
                'Cannot enforce 10-digit phone numbers. Resolve invalid values first: '.$invalidPhoneNumbers->implode(', ')
            );
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number', 10)->change();
        });

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE users ADD CONSTRAINT users_phone_number_ten_digits CHECK (char_length(phone_number) = 10 AND phone_number REGEXP "^[0-9]{10}$")');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE users DROP CHECK users_phone_number_ten_digits');
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->change();
        });
    }
};
