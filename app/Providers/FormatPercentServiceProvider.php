<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FormatPercentServiceProvider extends ServiceProvider {

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
        $this->app->bind('formatPercent', function () {
            $formatter = new \NumberFormatter(config('app.locale'), \NumberFormatter::PERCENT);
            $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 2);
            return $formatter;
        });

	}
}
