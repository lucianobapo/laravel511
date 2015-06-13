<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Moltin\Currency\Currency;
use Moltin\Currency\Exchange\OpenExchangeRates;
use Moltin\Currency\Format\Runtime;

class CurrencyServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
        //
    }

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app->bind('currency', function () {
            $exchange = new OpenExchangeRates(config('services.openExchangeRates.appId'));
            $runtime = new Runtime;
            $runtime->available['BRL'] = [
                'format'      => 'R${price}',
                'decimal'     => ',',
                'thousand'    => '.'
            ];
            return new Currency($exchange, $runtime);
        });

	}
}
