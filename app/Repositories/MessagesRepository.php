<?php namespace App\Repositories;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;

class MessagesRepository{

    private $fields= [];

//    public function __construct(Array $fields){
//        $this->fields = $fields;
//    }
    /**
     * @param array $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }


    public static function sendUserCreated(Array $fields)
    {
        $func = new static;
        $func->setFields($fields);

        $func->messageUserCreated();
    }
    /**
     * Send message if the User successful created.
     */
    public function messageUserCreated(){
        return Mail::send('emails.userCreated', $this->fields, function($message) {
            $message->to($this->fields['email'], $this->fields['name'])->subject(trans('email.userCreatedSubject',['user'=>$this->fields['user']->name]));
        });
    }

    public static function sendOrderCreated(Array $fields)
    {
        $func = new static;
        $func->setFields($fields);

        $func->messageOrderCreated();
    }
    public function messageOrderCreated(){
        return Mail::send('emails.orderCreated', $this->fields, function($message) {
            $message->to($this->fields['email'], $this->fields['name'])->subject(trans('email.orderCreatedSubject',['ordem'=>$this->fields['order']->id]));
        });
    }


    public static function sendUserLogin(Array $fields)
    {
        $func = new static;
        $func->setFields($fields);
        $func->messageUserLogin();
    }
    /**
     * Send message if the User successful created.
     */
    public function messageUserLogin(){
        return Mail::send('emails.userLogin', $this->fields, function($message) {
            $message->to($this->fields['email'], $this->fields['name'])->subject(trans('email.userLoginSubject',['user'=>$this->fields['user']->name]));
        });
    }

    public static function sendConfirmation(Array $fields)
    {
        $func = new static;
        $func->setFields($fields);
        $func->messageConfirmation();
    }
    /**
     * Send message if the Order successful confirmed.
     */
    public function messageConfirmation(){
//        dd($this->fields);
        return Mail::send('emails.orderConfirmation', $this->fields, function($message) {
            $message->to($this->fields['email'], $this->fields['name'])
                ->bcc($this->fields['bccEmail'],$this->fields['bccName'])
                ->subject(trans('email.orderConfirmationSubject',['ordem'=>$this->fields['order']->id]));
        });
    }
}