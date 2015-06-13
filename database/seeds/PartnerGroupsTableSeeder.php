<?php
use App\Models\Partner;
use App\Models\PartnerGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartnerGroupsTableSeeder extends Seeder
{

    public function run()
    {
        if (DB::connection()->getName() == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS = 0'); // disable foreign key constraints
        PartnerGroup::truncate();

        DB::table('partner_partner_group')->truncate();

        PartnerGroup::create([
            'mandante' => config('app.mandante'),
            'grupo' => 'Cliente',
        ]);
        PartnerGroup::create([
            'mandante' => config('app.mandante'),
            'grupo' => 'Fornecedor',
        ]);
        PartnerGroup::create([
            'mandante' => config('app.mandante'),
            'grupo' => 'Associado',
        ]);

        if (DB::connection()->getName() == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS = 1'); // enable foreign key constraints
    }
}