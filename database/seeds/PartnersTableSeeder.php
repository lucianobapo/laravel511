<?php
use App\Models\Contact;
use App\Models\Document;
use App\Models\Partner;
use App\Models\PartnerGroup;
use App\Models\ProductGroup;
use App\Models\SharedStat;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartnersTableSeeder extends Seeder
{

    public function run()
    {
//        $faker = Faker\Factory::create();

        if (DB::connection()->getName() == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS = 0'); // disable foreign key constraints
        Partner::truncate();
        Contact::truncate();
        Document::truncate();

        $oldPartner = (new \App\Models\OldPartner)->listar();
//        dd($oldPartner);

        foreach ($oldPartner as $partner) {
            //dd(Carbon::now()->timezone('America/Sao_Paulo'));
            $extra =[];
            if (!empty($partner->aniversario)){
                $extra = [
                    'data_nascimento' => Carbon::createFromTimestamp($partner->aniversario)->timezone('America/Sao_Paulo')->format('Y-m-d'),
                ];
//                dd(Carbon::createFromTimestamp($partner->aniversario)->timezone('America/Sao_Paulo')->format('Y-m-d'));
            }

            $newPartner = Partner::create([
                'mandante' => config('app.mandante'),
                'old_id' => $partner->id,
                'nome' => $partner->nome,
                'observacao' => $partner->obs,
            ]+$extra);

            if (!empty($partner->email)){
                Contact::create([
                    'mandante' => config('app.mandante'),
                    'partner_id' => $newPartner->id,
                    'contact_type' => 'email',
                    'contact_data' => $partner->email,
                ]);
            }

            if (!empty($partner->telefone)){
                Contact::create([
                    'mandante' => config('app.mandante'),
                    'partner_id' => $newPartner->id,
                    'contact_type' => 'telefone',
                    'contact_data' => $partner->telefone,
                ]);
            }

            $id = str_replace('.','',trim($partner->cnpj));
            $id = str_replace('/','',$id);
            $id = str_replace('-','',$id);
            if ( (!empty($id))&&($id!='000') ){
                if (strlen($id)==11) {
                    Document::create([
                        'mandante' => config('app.mandante'),
                        'partner_id' => $newPartner->id,
                        'document_type' => 'cpf',
                        'document_data' => $id,
                    ]);
                }
                elseif (strlen($id)==14) {
                    Document::create([
                        'mandante' => config('app.mandante'),
                        'partner_id' => $newPartner->id,
                        'document_type' => 'cnpj',
                        'document_data' => $id,
                    ]);
                }
                else{
                    dd($id);
                }
            }

            if ($partner->ativado){
                $newPartner->status()->sync([0=>SharedStat::where(['status' => 'ativado'])->first()->id]);
            }else{
                $newPartner->status()->sync([0=>SharedStat::where(['status' => 'desativado'])->first()->id]);
            }

            $grupos = [];

            if ($partner->tipo_cliente){
                $grupos[] = PartnerGroup::where(['grupo' => 'Cliente'])->first()->id;
            }
            if($partner->tipo_fornecedor){
                $grupos[] = PartnerGroup::where(['grupo' => 'Fornecedor'])->first()->id;
            }
            if($partner->tipo_associado){
                $grupos[] = PartnerGroup::where(['grupo' => 'Associado'])->first()->id;
            }
            $newPartner->groups()->sync($grupos);
        }


//        foreach (range(1, 6) as $index) {
//            Partner::create([
//                'mandante' => 'teste',
//                'nome' => $faker->sentence(2),
//            ]);
//        }

        if (DB::connection()->getName() == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS = 1'); // enable foreign key constraints
    }
}