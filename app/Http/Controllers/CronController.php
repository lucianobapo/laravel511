<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Repositories\MessagesRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;

class CronController extends Controller
{
    public function cron($minutes){
//        $command = "mysql -u homestead -psecret " . $this->argument('name') . "<" . $this->argument('import');
        $command = "ll";
        $process = new Process($command);
        $process->run();

//        $this->backupDatabase();
//        $this->checkOpenOrders();
    }

    protected function backupDatabase()
    {
        $date = Carbon::now()->toW3cString();
        $environment = env('APP_ENV');

        $result = Artisan::call("db:backup", [
//            "--filename" => "test.sql",
            "--compression" => "gzip",
            "--database" => "mysql_admin",
            "--destination" => config('delivery.backup_destination'),
            "--destinationPath" => "backups/{$environment}-{$date}",
        ]);
    }

    protected function checkOpenOrders()
    {
        $orders = Order::with('status', 'type', 'confirmations', 'partner', 'partner.user')
            ->get()
            ->filter(function ($item) {
                if ((strpos($item->status_list, 'Aberto') !== false) && ($item->type->tipo == 'ordemVenda'))
                    return $item;
            });

        foreach ($orders as $order) {
            if ((count($order->confirmations) == 0) && (config('delivery.newOrderEmailAlert'))) {
                MessagesRepository::sendOrderCreated([
                    'name' => config('mail.from.name'),
                    'email' => config('mail.from.address'),
                    'user' => isset($order->partner->user) ? $order->partner->user : null,
                    'partner' => $order->partner,
                    'order' => $order,
                ]);
            };
        }
    }
}
