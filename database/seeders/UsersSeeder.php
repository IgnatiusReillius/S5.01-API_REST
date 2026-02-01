<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->admin()->create([
            'email' => 'admin@biblio.com',
            'password' => 'password',
        ]);

        User::factory()->create([
            'email' => 'imclaughlin@example.org',
            'password' => 'password',
        ]);
        User::factory()->create([
            'email' => 'hansen.nicola@example.net',
            'password' => 'password',
        ]);
        User::factory()->create([
            'email' => 'donnell.mckenzie@example.com',
            'password' => 'password',
        ]);
        
        // $dataTables = [
        //     [
        //         'id' => 1,
        //         'name' => 'Albert',
        //         'email' => 'gines@aim.com',
        //         'email_verified_at' => NULL,
        //         'password' => '$2y$12$1dQLJhjUm4NXypoIYfi0J.zVGxezDJSIkDY5PFrEGLbeR0FN.WbXO',
        //         'is_admin' => true,
        //         'remember_token' => NULL,
        //         'created_at' => NULL,
        //         'updated_at' => NULL,
        //     ],
        //     [
        //         'id' => 2,
        //         'name' => 'Ayanna Manning',
        //         'email' => 'costa@hotmail.com',
        //         'email_verified_at' => NULL,
        //         'password' => '$2y$12$pgRkaJX9EJR0V.nOrV.M4.Ch0DSJBKtfH.FQubh1WCSCeWaZ1ntAm',
        //         'is_admin' => false,
        //         'remember_token' => NULL,
        //         'created_at' => NULL,
        //         'updated_at' => NULL,
        //     ],
        //     [
        //         'id' => 3,
        //         'name' => 'Antonia Salazar',
        //         'email' => 'plasienta@scientist.com',
        //         'email_verified_at' => NULL,
        //         'password' => '$b4U@M5UVPaCbC43pon$h6mrg^InzMp4dz$BIfJgq732JYPhq^0a@!rl9&RF',
        //         'is_admin' => false,
        //         'remember_token' => NULL,
        //         'created_at' => NULL,
        //         'updated_at' => NULL,
        //     ],
        //     [
        //         'id' => 4,
        //         'name' => 'Eva Toro',
        //         'email' => 'arjona@post.com',
        //         'email_verified_at' => NULL,
        //         'password' => '%592TW1$Lg1kCqyrmUr*CetSHC!JYzlD@qsa0LMn700%JOaesYmCrYJa#X@l',
        //         'is_admin' => false,
        //         'remember_token' => NULL,
        //         'created_at' => NULL,
        //         'updated_at' => NULL,
        //     ]
        // ];
        
        // DB::table("users")->insert($dataTables);
    }
}