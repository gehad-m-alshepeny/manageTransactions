<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            TransactionStatusSeeder::class,
        ]);

        // Create admin1 with admin role
       $admin = User::updateOrCreate(['email' => 'admin@gmail.com'],[
        'name' => 'admin',
        'email' => 'admin@gmail.com',
        'password' => bcrypt(env('ADMIN_PASSWORD','admin')),
        'role_id' => ADMIN,
        'email_verified_at' => now(),   
        'remember_token' => Str::random(10),
      ]);
      // Create admin2 with admin role
      $admin = User::updateOrCreate(['email' => 'admin2@gmail.com'],[
        'name' => 'admin2',
        'email' => 'admin2@gmail.com',
        'password' => bcrypt(env('ADMIN_PASSWORD','admin2')),
        'role_id' => ADMIN,
        'email_verified_at' => now(),   
        'remember_token' => Str::random(10),
      ]);

      // Create user with user role
      $user = User::updateOrCreate(['email' => 'user@gmail.com'],[
        'name' => 'user',
        'email' => 'user@gmail.com',
        'password' => bcrypt(env('ADMIN_PASSWORD','user')),
        'role_id' => USER,
        'email_verified_at' => now(),   
        'remember_token' => Str::random(10),
      ]);

       // Create user2 with user role
       $user = User::updateOrCreate(['email' => 'user2@gmail.com'],[
        'name' => 'user2',
        'email' => 'user2@gmail.com',
        'password' => bcrypt(env('ADMIN_PASSWORD','user2')),
        'role_id' => USER,
        'email_verified_at' => now(),   
        'remember_token' => Str::random(10),
      ]);

    }
}
