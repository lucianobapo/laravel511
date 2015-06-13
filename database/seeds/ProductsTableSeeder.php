<?php

use App\Models\Product;
use App\Models\SharedStat;
use App\Models\SharedUnitOfMeasure;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductsTableSeeder extends Seeder
{

    public function run()
    {
//        $randomSteatment = (DB::connection()->getName()=='mysql')?'RAND()':((DB::connection()->getName()=='sqlite')?'RANDOM()':'');

        $oldProducts = (new \App\Models\OldProduct)->listar();
//        dd();
//        $faker = Faker\Factory::create();
//        $faker->seed(1234);

        $imageDir = config('filesystems.imageLocation') . DIRECTORY_SEPARATOR;

        if (DB::connection()->getName()=='mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS = 0'); // disable foreign key constraints
        Product::truncate();
        Storage::deleteDirectory($imageDir);

        foreach($oldProducts as $product){
            $uomId = SharedUnitOfMeasure::where(['uom'=>$product->uom])->first()->id;
            $fileName = null;
            $fileUrl = env('SEED_OLD_IMAGES_PATH','/var/www/appyii_v1/').'images/'.$product->id.'.png';
            $fileUrlResized = env('SEED_OLD_IMAGES_PATH','/var/www/appyii_v1/').'images/resized-'.$product->id.'.png';
            if (file_exists($fileUrl)) {
                $fileName = 'imagem-de-'.str_slug($product->descricao).'.png';
                $image = new \App\Repositories\ImageRepository();
                $image->load($fileUrl);
                $image->resizeToHeight(150);
                $image->save($fileUrlResized,IMAGETYPE_PNG);
                if (!Storage::exists($imageDir)) Storage::makeDirectory($imageDir);
                Storage::put($imageDir . $fileName, file_get_contents($fileUrlResized));
            } //else dd($fileUrl);


            $newProduct = Product::create([
                'mandante' => config('app.mandante'),
                'uom_id' => $uomId,
//                'cost_id' => $costId,
                'nome' => $product->descricao,
                'imagem' => $fileName,

                'old_id' => $product->id,
                'promocao' => $product->promocao,
                'cod_fiscal' => $product->cod_fiscal,
                'cod_barra' => $product->cod_barra,
                'valorUnitVenda' => $product->valor_venda,
                'valorUnitVendaPromocao' => $product->valor_promocao,
                'valorUnitCompra' => 0,
            ]);

            if ($product->ativado){
                $newProduct->status()->sync([0=>SharedStat::where(['status' => 'ativado'])->first()->id]);
            }else{
                $newProduct->status()->sync([0=>SharedStat::where(['status' => 'desativado'])->first()->id]);
            }
        }

//        foreach (range(1, 10) as $index) {
//            $uomId = SharedUnitOfMeasure::orderBy(DB::raw($randomSteatment))->first()->id;
//            $costId = CostAllocate::orderBy(DB::raw($randomSteatment))->first()->id;
//            $fileUrl = $faker->imageUrl(150, 150, 'food');
////            $fileName = md5(\Carbon\Carbon::now()).'.jpg';
//            $name = $faker->sentence(2);
//            $fileName = 'imagem-de-'.str_slug($name).'.jpg';
//
//            if (!Storage::exists($imageDir)) Storage::makeDirectory($imageDir);
//            Storage::put($imageDir . $fileName, file_get_contents($fileUrl));
//
//            Product::create([
//                'mandante' => 'teste',
//                'uom_id' => $uomId,
//                'cost_id' => $costId,
//                'nome' => $name,
//                'imagem' => $fileName,
//
//                'promocao' => $faker->boolean(),
//                'valorUnitVenda' => $faker->randomFloat(2, 5, 100),
//                'valorUnitVendaPromocao' => $faker->randomFloat(2, 5, 100),
//                'valorUnitCompra' => $faker->randomFloat(2, 5, 100),
//            ]);
//
//        }

        if (DB::connection()->getName()=='mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS = 1'); // enable foreign key constraints
    }
}