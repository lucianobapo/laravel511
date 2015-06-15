<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();
//        dd(get_class($this));

//////        $this->call('TagsTableSeeder');
//////		$this->call('ArticlesTableSeeder');

        $this->call('SharedCurrenciesTableSeeder');
        $this->call('SharedOrderPaymentsTableSeeder');
        $this->call('SharedOrderTypesTableSeeder');
        $this->call('SharedUnitOfMeasuresTableSeeder');

        $this->call('SharedStatsTableSeeder');

        $this->call('CostAllocatesTableSeeder');

        $this->call('ProductsTableSeeder');
        $this->call('ProductGroupsTableSeeder');

        $this->call('PartnerGroupsTableSeeder');
        $this->call('PartnersTableSeeder');
        $this->call('UsersTableSeeder');

        $this->call('AddressesTableSeeder');
        $this->call('OrdersTableSeeder');
        $this->call('PurchaseOrdersTableSeeder');
        $this->call('RoleTableSeeder');
	}

}
