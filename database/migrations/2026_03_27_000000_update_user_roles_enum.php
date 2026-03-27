<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement(
                "ALTER TABLE users MODIFY role ENUM('superadmin', 'profile_manager', 'content_editor', 'user') NOT NULL DEFAULT 'user'"
            );
            DB::statement("UPDATE users SET role = 'profile_manager' WHERE role = 'admin'");

            return;
        }

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=off');
            DB::statement('CREATE TABLE users_new (id integer primary key autoincrement not null, name varchar not null, email varchar unique, phone_number varchar not null unique, username varchar unique, email_verified_at datetime, password varchar, remember_token varchar, created_at datetime, updated_at datetime, role varchar check (role in (\'superadmin\', \'profile_manager\', \'content_editor\', \'user\')) not null default \'user\', verification_step varchar check (verification_step in (\'unverified\', \'step1_complete\', \'step2_pending\', \'approved\')) not null default \'unverified\', profile_picture varchar)');
            DB::statement("INSERT INTO users_new (id, name, email, phone_number, username, email_verified_at, password, remember_token, created_at, updated_at, role, verification_step, profile_picture) SELECT id, name, email, phone_number, username, email_verified_at, password, remember_token, created_at, updated_at, CASE WHEN role = 'admin' THEN 'profile_manager' ELSE role END, verification_step, profile_picture FROM users");
            DB::statement('DROP TABLE users');
            DB::statement('ALTER TABLE users_new RENAME TO users');
            DB::statement('PRAGMA foreign_keys=on');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement(
                "ALTER TABLE users MODIFY role ENUM('superadmin', 'admin', 'user') NOT NULL DEFAULT 'user'"
            );
            DB::statement("UPDATE users SET role = 'admin' WHERE role = 'profile_manager'");
            DB::statement("UPDATE users SET role = 'user' WHERE role = 'content_editor'");

            return;
        }

        if ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys=off');
            DB::statement('CREATE TABLE users_old (id integer primary key autoincrement not null, name varchar not null, email varchar unique, phone_number varchar not null unique, username varchar unique, email_verified_at datetime, password varchar, remember_token varchar, created_at datetime, updated_at datetime, role varchar check (role in (\'superadmin\', \'admin\', \'user\')) not null default \'user\', verification_step varchar check (verification_step in (\'unverified\', \'step1_complete\', \'step2_pending\', \'approved\')) not null default \'unverified\', profile_picture varchar)');
            DB::statement("INSERT INTO users_old (id, name, email, phone_number, username, email_verified_at, password, remember_token, created_at, updated_at, role, verification_step, profile_picture) SELECT id, name, email, phone_number, username, email_verified_at, password, remember_token, created_at, updated_at, CASE WHEN role = 'profile_manager' THEN 'admin' WHEN role = 'content_editor' THEN 'user' ELSE role END, verification_step, profile_picture FROM users");
            DB::statement('DROP TABLE users');
            DB::statement('ALTER TABLE users_old RENAME TO users');
            DB::statement('PRAGMA foreign_keys=on');
        }
    }
};
