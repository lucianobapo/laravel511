<?php

namespace App\Console;

use App\Models\Order;
use App\Models\OrderConfirmation;
use App\Repositories\MessagesRepository;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $filePath = storage_path('logs').DIRECTORY_SEPARATOR.'scheduled.log';

//        $schedule->command('inspire')
//                 ->hourly();

        $schedule->command('backup:run')
            ->dailyAt('06:03')
            ->sendOutputTo($filePath)
            ->emailOutputTo(config('mail.from.address'));

        $schedule->call(function () {
            $orders = Order::with('status','type','confirmations','partner','partner.user')
                ->get()
                ->filter(function($item) {
                    if ( (strpos($item->status_list,'Aberto')!==false) && ($item->type->tipo=='ordemVenda') )
                        return $item;
                });

            foreach ($orders as $order) {
                if (count($order->confirmations)==0){
                    MessagesRepository::sendOrderCreated([
                        'name'=>config('mail.from.name'),
                        'email'=>config('mail.from.address'),
                        'user'=>isset($order->partner->user)?$order->partner->user:null,
                        'partner'=>$order->partner,
                        'order'=>$order,
                    ]);
                };
            }
        })
            ->everyTenMinutes();
//            ->everyMinute();
    }
}
