<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('user_profile') && ! Schema::hasColumn('user_profile', 'address')) {
            Schema::table('user_profile', function (Blueprint $table): void {
                $table->text('address')->nullable();
            });
        }

        if (
            Schema::hasTable('user_profile')
            && Schema::hasColumn('user_profile', 'mumbai_address')
            && Schema::hasColumn('user_profile', 'village_address')
            && Schema::hasColumn('user_profile', 'address')
        ) {
            $profiles = DB::table('user_profile')
                ->select(['id', 'address', 'mumbai_address', 'village_address'])
                ->get();

            foreach ($profiles as $profile) {
                $existingAddress = trim((string) $profile->address);

                if ($existingAddress !== '') {
                    continue;
                }

                $parts = [];

                $mumbaiAddress = trim((string) $profile->mumbai_address);
                if ($mumbaiAddress !== '') {
                    $parts[] = $mumbaiAddress;
                }

                $villageAddress = trim((string) $profile->village_address);
                if ($villageAddress !== '' && ! in_array($villageAddress, $parts, true)) {
                    $parts[] = $villageAddress;
                }

                $mergedAddress = implode("\n", $parts);

                if ($mergedAddress !== '') {
                    DB::table('user_profile')
                        ->where('id', $profile->id)
                        ->update(['address' => $mergedAddress]);
                }
            }

            Schema::table('user_profile', function (Blueprint $table): void {
                $table->dropColumn('mumbai_address');
                $table->dropColumn('village_address');
            });
        }

        if (Schema::hasTable('edit_user_profiles') && ! Schema::hasColumn('edit_user_profiles', 'address')) {
            Schema::table('edit_user_profiles', function (Blueprint $table): void {
                $table->text('address')->nullable();
            });
        }

        if (
            Schema::hasTable('edit_user_profiles')
            && Schema::hasColumn('edit_user_profiles', 'mumbai_address')
            && Schema::hasColumn('edit_user_profiles', 'village_address')
            && Schema::hasColumn('edit_user_profiles', 'address')
        ) {
            $pendingEdits = DB::table('edit_user_profiles')
                ->select(['id', 'address', 'mumbai_address', 'village_address'])
                ->get();

            foreach ($pendingEdits as $pendingEdit) {
                $existingAddress = trim((string) $pendingEdit->address);

                if ($existingAddress !== '') {
                    continue;
                }

                $parts = [];

                $mumbaiAddress = trim((string) $pendingEdit->mumbai_address);
                if ($mumbaiAddress !== '') {
                    $parts[] = $mumbaiAddress;
                }

                $villageAddress = trim((string) $pendingEdit->village_address);
                if ($villageAddress !== '' && ! in_array($villageAddress, $parts, true)) {
                    $parts[] = $villageAddress;
                }

                $mergedAddress = implode("\n", $parts);

                if ($mergedAddress !== '') {
                    DB::table('edit_user_profiles')
                        ->where('id', $pendingEdit->id)
                        ->update(['address' => $mergedAddress]);
                }
            }

            Schema::table('edit_user_profiles', function (Blueprint $table): void {
                $table->dropColumn('mumbai_address');
                $table->dropColumn('village_address');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('user_profile') && ! Schema::hasColumn('user_profile', 'mumbai_address')) {
            Schema::table('user_profile', function (Blueprint $table): void {
                $table->text('mumbai_address')->nullable();
            });
        }

        if (Schema::hasTable('user_profile') && ! Schema::hasColumn('user_profile', 'village_address')) {
            Schema::table('user_profile', function (Blueprint $table): void {
                $table->text('village_address')->nullable();
            });
        }

        if (
            Schema::hasTable('user_profile')
            && Schema::hasColumn('user_profile', 'address')
            && Schema::hasColumn('user_profile', 'mumbai_address')
            && Schema::hasColumn('user_profile', 'village_address')
        ) {
            $profiles = DB::table('user_profile')
                ->select(['id', 'address'])
                ->get();

            foreach ($profiles as $profile) {
                $address = trim((string) $profile->address);

                if ($address !== '') {
                    DB::table('user_profile')
                        ->where('id', $profile->id)
                        ->update([
                            'mumbai_address' => $address,
                            'village_address' => null,
                        ]);
                }
            }

            Schema::table('user_profile', function (Blueprint $table): void {
                $table->dropColumn('address');
            });
        }

        if (Schema::hasTable('edit_user_profiles') && ! Schema::hasColumn('edit_user_profiles', 'mumbai_address')) {
            Schema::table('edit_user_profiles', function (Blueprint $table): void {
                $table->text('mumbai_address')->nullable();
            });
        }

        if (Schema::hasTable('edit_user_profiles') && ! Schema::hasColumn('edit_user_profiles', 'village_address')) {
            Schema::table('edit_user_profiles', function (Blueprint $table): void {
                $table->text('village_address')->nullable();
            });
        }

        if (
            Schema::hasTable('edit_user_profiles')
            && Schema::hasColumn('edit_user_profiles', 'address')
            && Schema::hasColumn('edit_user_profiles', 'mumbai_address')
            && Schema::hasColumn('edit_user_profiles', 'village_address')
        ) {
            $pendingEdits = DB::table('edit_user_profiles')
                ->select(['id', 'address'])
                ->get();

            foreach ($pendingEdits as $pendingEdit) {
                $address = trim((string) $pendingEdit->address);

                if ($address !== '') {
                    DB::table('edit_user_profiles')
                        ->where('id', $pendingEdit->id)
                        ->update([
                            'mumbai_address' => $address,
                            'village_address' => null,
                        ]);
                }
            }

            Schema::table('edit_user_profiles', function (Blueprint $table): void {
                $table->dropColumn('address');
            });
        }
    }
};
