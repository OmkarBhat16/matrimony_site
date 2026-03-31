<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('user_profile')) {
            $profiles = DB::table('user_profile')
                ->select(['id', 'user_id', 'full_name', 'gender', 'primary_image'])
                ->orderBy('id')
                ->get();

            foreach ($profiles as $profile) {
                $user = DB::table('users')
                    ->select(['name', 'gender'])
                    ->where('id', $profile->user_id)
                    ->first();

                $updates = [];

                if (($profile->full_name === null || $profile->full_name === '') && $user?->name) {
                    $updates['full_name'] = $user->name;
                }

                if (($profile->gender === null || $profile->gender === '') && $user?->gender) {
                    $updates['gender'] = $user->gender;
                }

                if ($profile->primary_image === null) {
                    $updates['primary_image'] = 1;
                }

                if ($updates !== []) {
                    DB::table('user_profile')
                        ->where('id', $profile->id)
                        ->update($updates);
                }
            }
        }

        if (Schema::hasTable('users')) {
            if (DB::getDriverName() === 'sqlite') {
                DB::statement(<<<'SQL'
                    CREATE TABLE users_new (
                        id integer primary key autoincrement not null,
                        name varchar not null,
                        email varchar unique,
                        phone_number varchar not null unique,
                        email_verified_at datetime,
                        password varchar,
                        remember_token varchar,
                        created_at datetime,
                        updated_at datetime,
                        role varchar check (role in ('superadmin', 'profile_manager', 'content_editor', 'user')) not null default 'user',
                        verification_step varchar check (verification_step in ('unverified', 'step1_complete', 'step2_pending', 'approved')) not null default 'unverified',
                        "public_id" varchar unique,
                        "gender" varchar check ("gender" in ('male', 'female', 'other')),
                        "deleted_at" datetime
                    )
                SQL);

                DB::statement(<<<'SQL'
                    INSERT INTO users_new (
                        id, name, email, phone_number, email_verified_at, password, remember_token,
                        created_at, updated_at, role, verification_step, public_id, gender, deleted_at
                    )
                    SELECT
                        id, name, email, phone_number, email_verified_at, password, remember_token,
                        created_at, updated_at, role, verification_step, public_id, gender, deleted_at
                    FROM users
                SQL);

                DB::statement('DROP TABLE users');
                DB::statement('ALTER TABLE users_new RENAME TO users');
            } else {
                Schema::table('users', function (Blueprint $table): void {
                    if (Schema::hasColumn('users', 'username')) {
                        $table->dropUnique('users_username_unique');
                        $table->dropColumn('username');
                    }

                    if (Schema::hasColumn('users', 'profile_picture')) {
                        $table->dropColumn('profile_picture');
                    }
                });
            }
        }

        if (Schema::hasTable('user_profile') && Schema::hasColumn('user_profile', 'weight_kg')) {
            Schema::table('user_profile', function (Blueprint $table): void {
                $table->dropColumn('weight_kg');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table): void {
                if (! Schema::hasColumn('users', 'username')) {
                    $table->string('username')->nullable();
                    $table->unique('username');
                }

                if (! Schema::hasColumn('users', 'profile_picture')) {
                    $table->string('profile_picture')->nullable();
                }
            });
        }

        if (Schema::hasTable('user_profile')) {
            Schema::table('user_profile', function (Blueprint $table): void {
                if (! Schema::hasColumn('user_profile', 'weight_kg')) {
                    $table->decimal('weight_kg', 8, 2)->nullable();
                }
            });
        }
    }
};
