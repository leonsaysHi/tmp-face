<?php

use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();

        $user->email    = sprintf("cloudappsteam+%s@gmail.com", env('APP_NAME'));
        $user->name    ='cloudappsteam';
        $user->password = bcrypt(env('ADMIN_PASSWORD'));
        $user->is_super_admin = true;
        $user->user_type = 'Manual';
        $user->save();

        $user = new User();
        $user->email    ="jose@gmail.com";
        $user->name    ='jose';
        $user->password = bcrypt("password");
        $user->user_type = 'Manual';
        $user->save();
    }

    public function auditUser()
    {
        //2 per role
        User::create([
            'email' => 'cloudappsteam+security-scanner-admin1@gmail.com',
            'password' => bcrypt(env('SCANNER_ADMIN_PASSWORD')),
        ]);
        User::create([
            'email' => 'cloudappsteam+security-scanner-admin2@gmail.com',
            'password' => bcrypt(env('SCANNER_ADMIN_PASSWORD')),
        ]);
        User::create([
            'email' => 'cloudappsteam+security-scanner-non-admin1@gmail.com',
            'password' => bcrypt(env('SCANNER_NON_ADMIN_PASSWORD')),
        ]);
        User::create([
            'email' => 'cloudappsteam+security-scanner-non-admin2@gmail.com',
            'password' => bcrypt(env('SCANNER_NON_ADMIN_PASSWORD')),
        ]);
    }
}
