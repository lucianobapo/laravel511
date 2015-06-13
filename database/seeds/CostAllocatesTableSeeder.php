<?php
use App\Models\CostAllocate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CostAllocatesTableSeeder extends Seeder
{

    public function run()
    {
//        $faker = Faker\Factory::create();

        if (DB::connection()->getName() == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS = 0'); // disable foreign key constraints
        CostAllocate::truncate();

        CostAllocate::create([
            'mandante' => config('app.mandante'),
            'numero' => '01',
            'nome' => 'Mercadorias',
            'descricao' => 'Mercadorias',
        ]);
        CostAllocate::create([
            'mandante' => config('app.mandante'),
            'numero' => '02',
            'nome' => 'Lanches',
            'descricao' => 'Lanches',
        ]);
        CostAllocate::create([
            'mandante' => config('app.mandante'),
            'numero' => '03',
            'nome' => 'Ativos',
            'descricao' => 'Ativos',
        ]);
        CostAllocate::create([
            'mandante' => config('app.mandante'),
            'numero' => '04',
            'nome' => 'Despesas',
            'descricao' => 'Despesas',
        ]);
        CostAllocate::create([
            'mandante' => config('app.mandante'),
            'numero' => '05',
            'nome' => 'Impostos',
            'descricao' => 'Impostos',
        ]);
        CostAllocate::create([
            'mandante' => config('app.mandante'),
            'numero' => '06',
            'nome' => 'Transporte',
            'descricao' => 'Transporte',
        ]);

//        foreach (range(1, 5) as $index) {
//            CostAllocate::create([
//                'mandante' => 'teste',
//                'numero' => $faker->randomNumber(2),
//                'nome' => ucfirst($faker->word),
//                'descricao' => $faker->sentence(2),
//            ]);
//        }

        if (DB::connection()->getName() == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS = 1'); // enable foreign key constraints
    }
}