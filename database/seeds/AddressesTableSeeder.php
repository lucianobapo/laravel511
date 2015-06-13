<?php

use App\Models\Address;
use App\Models\Partner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressesTableSeeder extends Seeder
{

    public function run()
    {
        $randomSteatment = (DB::connection()->getName()=='mysql')?'RAND()':((DB::connection()->getName()=='sqlite')?'RANDOM()':'');

//        $faker = Faker\Factory::create();
//        $faker->addProvider(new \Faker\Provider\pt_BR\Address($faker));

        if (DB::connection()->getName() == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS = 0'); // disable foreign key constraints
        Address::truncate();

//        $oldAddress = (new \App\Models\OldAddress)->listar();
        $oldPartner = (new \App\Models\OldPartner)->listar();

//        dd($oldAddress);

        foreach($oldPartner as $partner){

            if (count($enderecos = (new \App\Models\OldAddress)->procuraEndereco($partner->id))>0 ){
                foreach($enderecos as $address) {
                    $partnerId = Partner::where(['old_id'=>$address->id_entidade])->first()->id;
                    Address::create([
                        'mandante' => config('app.mandante'),
                        'partner_id' => $partnerId,
                        'cep' => $address->cep,
                        'logradouro' => $address->logradouro,
                        'numero' => $address->complemento,
//                'complemento' => null,
                        'bairro' => $address->bairro,
                        'cidade' => $address->cidade,
                        'estado' => $address->estado,
                        'pais' => $address->pais,
                        'obs' => $address->obs,
                    ]);
                }
            } elseif(!empty($partner->endereco)) {
                $partnerId = Partner::where(['old_id'=>$partner->id])->first()->id;
                Address::create([
                    'mandante' => config('app.mandante'),
                    'partner_id' => $partnerId,
                    'cep' => empty(trim($partner->cep))?'28890000':$partner->cep,
                    'logradouro' => $partner->endereco,
                    'numero' => empty(trim($partner->custom4))?'S/N':$partner->custom4,
//                    'complemento' => null,
                    'bairro' => $partner->custom3,
                    'cidade' => $partner->cidade,
                    'estado' => $partner->estado,
//                    'pais' => $address->pais,
                    'obs' => $partner->obs,
                ]);
            }
        }

//        $partners = Partner::orderBy(DB::raw($randomSteatment))->get();
//        foreach ($partners as $partner) {
//            //$partnerId = \App\Partner::orderBy(DB::raw($randomSteatment))->first()->id;
//            foreach (range(1, 2) as $index) {
//                Address::create([
//                    'mandante' => 'teste',
////                    'partner_id' => $partnerId,
//                    'partner_id' => $partner->id,
//                    //'cep' => str_replace('-','',$faker->postcode(8)),
//                    'cep' => $faker->numberBetween(28890001,28899999),
//                    'logradouro' => $faker->streetName,
////                    'numero' => $faker->numberBetween(100,1000),
//                    'numero' => $faker->buildingNumber,
//                    'complemento' => $faker->secondaryAddress,
//                    'bairro' => $faker->word(5),
//                    'cidade' => $faker->city,
//                    'estado' => $faker->stateAbbr,
//                    'pais' => $faker->country,
//                    'obs' => $faker->sentence(3),
//                    //'principal ' => false,
//                    //'cancelado ' => false,
//                ]);
//            }
//        }

        if (DB::connection()->getName() == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS = 1'); // enable foreign key constraints
    }
}