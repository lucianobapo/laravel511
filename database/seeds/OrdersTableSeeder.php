<?php

use App\Models\Address;
use App\Models\CostAllocate;
use App\Models\ItemOrder;
use App\Models\Order;
use App\Models\Partner;
use App\Models\Product;
use App\Models\SharedCurrency;
use App\Models\SharedOrderPayment;
use App\Models\SharedOrderType;
use App\Models\SharedStat;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrdersTableSeeder extends Seeder
{

    public function run()
    {

        if (DB::connection()->getName() == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS = 0'); // disable foreign key constraints
        Order::truncate();
        ItemOrder::truncate();

        $oldOrder = (new \App\Models\OldOrder)->listarOrdens('venda');

        foreach($oldOrder as $order){
//            dd(Carbon::createFromTimestamp($order->data_termino));
            $partnerId = Partner::where(['old_id'=>$order->id_cliente])->first()->id;
            $currencyId = SharedCurrency::where(['nome_universal' => 'BRL'])->first()->id;

            $oldCost = (new \App\Models\OldOrder)->listarWbs($order->id_wbs)[0]->descricao;

            $costId = CostAllocate::where(['nome'=>$oldCost])->first();
            if (is_null($costId)) continue;
            $costId = CostAllocate::where(['nome'=>$oldCost])->first()->id;

            $newOrder = Order::create([
                'mandante' => config('app.mandante'),
                'partner_id' => $partnerId,
                'currency_id' => $currencyId,
                'type_id' => SharedOrderType::where(['tipo'=>'ordemVenda'])->first()->id,
                'payment_id' => SharedOrderPayment::where(['pagamento'=>$order->pagamento])->first()->id,
//                'posted_at' => Carbon::createFromTimestamp($order->data_termino)->timezone('America/Sao_Paulo')->format('Y-m-d'),
                'posted_at' => Carbon::createFromTimestamp($order->data_termino),
                'valor_total' => $order->valor,
//                'desconto_total',
//                'troco',
//                'descricao',
//                'referencia',
                'obsevacao' => $order->obs,
                'old_id' => $order->id,
            ]);

            $newOrder->status()->sync([0=>SharedStat::where(['status' => 'finalizado'])->first()->id]);

            $oldItens = (new \App\Models\OldOrder)->listarItens($order->id);
            foreach ($oldItens as $item) {
                ItemOrder::create([
                    'mandante' => config('app.mandante'),
                    'order_id' => $newOrder->id,
                    'currency_id' => $currencyId,
                    'cost_id' => $costId,
                    'product_id' => Product::where(['old_id' => $item->id_produto])->first()->id,
                    'quantidade' => $item->quantidade,
                    'valor_unitario' => $item->valor,
//                    'desconto_unitario',
//                    'descricao',
                ]);
            }
        }


        if (DB::connection()->getName() == 'mysql')
            DB::statement('SET FOREIGN_KEY_CHECKS = 1'); // enable foreign key constraints
    }
}