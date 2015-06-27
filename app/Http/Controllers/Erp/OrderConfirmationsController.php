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
    public function getIndex(Order $order, $host)
    {
        return view('erp.confirmations.index',compact('host'))->with([
            'orders' => $order->with('status','currency','type','payment','partner')->get()
                ->filter(function($item) {
                    if (strpos($item->status_list,'Aberto')!==false)
                        return $item;
                }),
        ]);        
    }

    public function getConfirm($host, $order){
        $orderFound = Order::find($order);
        return view('erp.confirmations.confirm',compact('host'))->with([
            'confirmations' => OrderConfirmation::where(['order_id'=>$order])->get(),
            'order' => $orderFound,
            'viewConfirmRecebido' => view('erp.confirmations.partials.confirmRecebido',compact('host'))->with([
                'order' => $orderFound,
            ]),
            'viewConfirmEntregando' => view('erp.confirmations.partials.confirmEntregando',compact('host'))->with([
                'order' => $orderFound,
            ]),
            'viewConfirmEntregue' => view('erp.confirmations.partials.confirmEntregue',compact('host'))->with([
                'order' => $orderFound,
            ]),
        ]);
    }

    public function postConfirm($host, Request $request){
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
//            dd($fields);
            OrderConfirmation::create($fields);
//            dd(($order->partner));
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
                    'msg' => $attributes['mensagem'],
                ]);

            flash()->overlay(trans('confirmation.flash.confirmed',['ordem'=>$attributes['order_id']]),trans('confirmation.flash.confirmedTitle'));

            return redirect(route('confirmations.index', $host));
        }
    }
}
