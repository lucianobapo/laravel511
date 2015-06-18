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
        return view('erp.confirmations.confirm',compact('host'))->with([
            'confirmations' => OrderConfirmation::where(['order_id'=>$order])->get(),
            'order' => Order::find($order),
        ]);
    }

    public function postConfirm($host, Request $request){
        if($request->method()==='POST'){
            $attributes = $request->all();
            $order = Order::find($attributes['order_id']);
            OrderConfirmation::create([
                'mandante' => Auth::user()->mandante,
                'order_id'  => $attributes['order_id'],
                'type' => $attributes['type'],
            ]);
//            dd(($order->partner));
            $sendMessage=false;
            foreach ($order->partner->contacts as $contact) {
                if ($contact->contact_type=='email') {
                    $email = $contact->contact_data;
                    $sendMessage=true;
                }
            }

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
