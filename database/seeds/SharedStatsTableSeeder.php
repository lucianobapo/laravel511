<?php
use App\Models\Partner;
use App\Models\SharedStat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SharedStatsTableSeeder extends Seeder
{

    public function run()
    {
        //$faker = Faker\Factory::create();

        if (DB::connection()->getName() == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS = 0'); // disable foreign key constraints
        SharedStat::truncate();
        DB::table('product_shared_stat')->truncate();
        DB::table('order_shared_stat')->truncate();

        SharedStat::create([
            'status' => 'aberto',
            'descricao' => 'Aberto',
        ]);
        SharedStat::create([
            'status' => 'finalizado',
            'descricao' => 'Finalizado',
        ]);
        SharedStat::create([
            'status' => 'cancelado',
            'descricao' => 'Cancelado',
        ]);
        SharedStat::create([
            'status' => 'principal',
            'descricao' => 'Principal',
        ]);
        SharedStat::create([
            'status' => 'usuario',
            'descricao' => 'Criado pelo Usuário',
        ]);
        SharedStat::create([
            'status' => 'ativado',
            'descricao' => 'Ativado',
        ]);
        SharedStat::create([
            'status' => 'desativado',
            'descricao' => 'Desativado',
        ]);
//        foreach (range(1, 6) as $index) {
//            SharedStat::create([
//                'mandante' => 'teste',
//                'status' => $faker->sentence(2),
//            ]);
//        }

        if (DB::connection()->getName() == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS = 1'); // enable foreign key constraints
    }
}