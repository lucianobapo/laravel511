<?php

use App\Models\Partner;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{

    public function run()
    {
//        $faker = Faker\Factory::create();
//        $faker->seed(1234);

        if (DB::connection()->getName()=='mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS = 0'); // disable foreign key constraints
        User::truncate();
//        Partner::truncate();
//        Contact::truncate();

        $oldUser = (new \App\Models\OldUser)->listar();

        foreach ($oldUser as $user) {
            if ($user->email=='amanda_vsa@hotmail.com') continue;
            if ($user->email=='ilhanet.lan@gmail.com') continue;
            if (empty($user->provider)) continue;
//            dd(config('app.mandante'));
            $partner = Partner::where(['old_id'=>$user->id_cliente])->first();
            $newUser = User::create([
                'mandante' => config('app.mandante'),
                'name'=> $partner->nome,
                'email'=> $user->email,
                'provider'=> strtolower($user->provider),
                'provider_id'=> $user->social_identifier,
            ],false);
            $newUser->partner()->save($partner);
        }



//        foreach (range(1, 10) as $index) {
//            User::create([
//                'mandante' => 'teste',
//                'name'=> $faker->name,
//                'email'=> $faker->email,
//                'password' => bcrypt('1234'),
//            ]);
//        }

        if (DB::connection()->getName()=='mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS = 1'); // enable foreign key constraints
    }
}