<?php

namespace Database\Seeders;

use App\Models\Resident;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role as SpatieRole;

class AdultResidentUserSeeder extends Seeder
{
    public function run(): void
    {
        $role = SpatieRole::firstOrCreate(['name' => 'Resident']);
        $password = Hash::make('password123');

        Resident::where('is_active', true)
            ->whereDate('birthdate', '<=', now()->subYears(18)->toDateString())
            ->whereDoesntHave('user')
            ->orderBy('id')
            ->chunkById(100, function ($residents) use ($password, $role) {
                $now = now();
                $users = [];

                foreach ($residents as $resident) {
                    $users[] = [
                        'name' => $resident->full_name,
                        'email' => $this->residentEmail($resident),
                        'password' => $password,
                        'role' => 'Resident',
                        'is_active' => true,
                        'resident_id' => $resident->id,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                if (!empty($users)) {
                    DB::table('users')->insertOrIgnore($users);
                }

                $residentIds = $residents->pluck('id');
                $createdUsers = User::whereIn('resident_id', $residentIds)->get(['id']);
                $roleRows = $createdUsers->map(fn ($user) => [
                    'role_id' => $role->id,
                    'model_type' => User::class,
                    'model_id' => $user->id,
                ])->all();

                if (!empty($roleRows)) {
                    DB::table('model_has_roles')->insertOrIgnore($roleRows);
                }
            });

        User::where('role', 'Resident')
            ->whereNotNull('resident_id')
            ->whereDoesntHave('roles', fn ($query) => $query->where('name', 'Resident'))
            ->chunkById(100, function ($users) use ($role) {
                $roleRows = $users->map(fn ($user) => [
                    'role_id' => $role->id,
                    'model_type' => User::class,
                    'model_id' => $user->id,
                ])->all();

                DB::table('model_has_roles')->insertOrIgnore($roleRows);
            });
    }

    private function residentEmail(Resident $resident): string
    {
        $baseName = Str::slug($resident->first_name . '.' . $resident->last_name, '.');
        $baseName = $baseName ?: 'resident';

        return $baseName . '.' . str_pad((string) $resident->id, 5, '0', STR_PAD_LEFT) . '@resident.barangay.local';
    }
}
