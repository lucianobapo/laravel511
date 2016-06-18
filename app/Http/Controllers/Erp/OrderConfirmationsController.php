<?php

namespace App\Http\Controllers\Erp;

use App\Models\Order;
use App\Models\OrderConfirmation;
use App\Repositories\MessagesRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderConfirmationsController extends Controller
{
//    public function getIndex(Order $order, $host)
//    {
//        return view('erp.confirmations.index',compact('host'))->with([
//            'orders' => $order->with('status','currency','type','payment','partner')->get()
//                ->filter(function($item) {
//                    if (strpos($item->status_list,'Aberto')!==false)
//                        return $item;
//                }),
//        ]);
//    }

    public function getConfirm($order){
        $orderFound = Order::find($order);
        return view('erp.confirmations.confirm')->with([
            'confirmations' => OrderConfirmation::where(['order_id'=>$order])->get(),
            'order' => $orderFound,
            'viewConfirmRecebido' => view('erp.confirmations.partials.confirmRecebido')->with([
                'order' => $orderFound,
            ]),
            'viewConfirmEntregando' => view('erp.confirmations.partials.confirmEntregando')->with([
                'order' => $orderFound,

            ]),
            'viewConfirmEntregue' => view('erp.confirmations.partials.confirmEntregue')->with([
                'order' => $orderFound,
            ]),
        ]);
    }

    public function postConfirm(Request $request){
        if($request->method()==='POST'){
            $attributes = $request->all();
            $order = Order::find($attributes['order_id']);
            $fields = [
                'mandante' => Auth::user()->mandante,
                'order_id' => $attributes['order_id'],
                'type' => $attributes['type'],
            ];
            if (isset($attributes['mensagem']))
                $fields['message'] = $attributes['mensagem'];
            if (isset($attributes['posted_at']))
                $fields['posted_at'] = $attributes['posted_at'];
            OrderConfirmation::create($fields);
            $sendMessage=false;
            foreach ($order->partner->contacts as $contact) {
                if ($contact->contact_type=='email') {
                    $email = $contact->contact_data;
                    $sendMessage=true;
                }
            }

            $sendMessage=isset($attributes['enviar'])?!!$attributes['enviar']:false;

            if ($sendMessage)
                MessagesRepository::sendConfirmation([
                    'bccName'=>config('mail.bcc')['name'],
                    'bccEmail'=>config('mail.bcc')['address'],
                    'name'=>$order->partner->nome,
                    'email'=>$email,
                    'user'=>is_null($order->partner->user)?null:$order->partner->user,
                    'partner'=>$order->partner,
                    'order'=>$order,
//                    'host'=>$host,
                    'msg' => $attributes['mensagem'],
                ]);

            flash()->overlay(trans('confirmation.flash.confirmed',['ordem'=>$attributes['order_id']]),trans('confirmation.flash.confirmedTitle'));

            return redirect(route('orders.abertas'));
        }
    }
}
