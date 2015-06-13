<?php
use App\Models\SharedUnitOfMeasure;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SharedUnitOfMeasuresTableSeeder extends Seeder
{

    public function run()
    {
//        $faker = Faker\Factory::create();

        if (DB::connection()->getName() == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS = 0'); // disable foreign key constraints
        SharedUnitOfMeasure::truncate();

        SharedUnitOfMeasure::create([
            'uom' => 'UN',
            'descricao' => 'UN - Unidade',
        ]);
        SharedUnitOfMeasure::create([
            'uom' => 'm3',
            'descricao' => 'm³ - Metro Cúbico',
        ]);
        SharedUnitOfMeasure::create([
            'uom' => 'm',
            'descricao' => 'm - Metro',
        ]);
        SharedUnitOfMeasure::create([
            'uom' => 'mm',
            'descricao' => 'mm - Milímetro',
        ]);
        SharedUnitOfMeasure::create([
            'uom' => 'l',
            'descricao' => 'l - Litro',
        ]);
        SharedUnitOfMeasure::create([
            'uom' => 'ml',
            'descricao' => 'ml - Mililitro',
        ]);
        SharedUnitOfMeasure::create([
            'uom' => 'CX',
            'descricao' => 'CX - Caixa',
        ]);
        SharedUnitOfMeasure::create([
            'uom' => 'Kg',
            'descricao' => 'Kg - Kilograma',
        ]);


//        foreach (range(1, 5) as $index) {
//            SharedUnitOfMeasure::create([
//                'uom' => strtoupper($faker->word(2)),
//                'descricao' => $faker->sentence(2),
//            ]);
//        }

        if (DB::connection()->getName() == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS = 1'); // enable foreign key constraints
    }
}