<?php

use App\Models\ProductGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductGroupsTableSeeder extends Seeder
{

    public function run()
    {
//        $faker = Faker\Factory::create();

        if (DB::connection()->getName() == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS = 0'); // disable foreign key constraints
        ProductGroup::truncate();
        DB::table('product_product_group')->truncate();

        ProductGroup::create([
            'mandante' => config('app.mandante'),
            'grupo' => 'Estoque Produção 3',
        ]);
        ProductGroup::create([
            'mandante' => config('app.mandante'),
            'grupo' => 'Estoque Revenda 8-18',
        ]);

        $oldProducts = (new \App\Models\OldProduct)->listar();

        foreach($oldProducts as $product){
            $productGroups = (new \App\Models\OldProduct)->listarGrupos($product->id);
            $newProductId = \App\Models\Product::where(['old_id'=>$product->id])->first()->id;
//            dd($newProductId);
            foreach ($productGroups as $group) {
//                dd($group->nome);
                $productGroup = ProductGroup::firstOrCreate([
                    'mandante' => config('app.mandante'),
                    'grupo' => $group->nome,
                ]);
                $productsArray = $productGroup->products()->get()->toArray();
                $newProductsArray = [];
//                dd(count($productsArray));
                if (count($productsArray)>0) {
//                    dd($productsArray);
                    foreach ($productsArray as $key=>$value) {
                        $newProductsArray[$key]=$value['id'];
                    }
                    $newProductsArray[$key+1]=$newProductId;
//                    dd($newProductsArray);
//                    dd($productGroup->products()->get()->toArray());
                }else{
                    $newProductsArray = [0=>$newProductId];
                }

                $productGroup->products()->sync($newProductsArray);
                if ($group->nome=='Delivery'){
                    $produtos = $productGroup->products()->get();
                    foreach ($produtos as $produto) {
                        $produto->estoque=true;
                        $produto->save();
                    }
                }

            }

//            dd($productGroups);

        }

        if (DB::connection()->getName() == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS = 1'); // enable foreign key constraints
    }
}