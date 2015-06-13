<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SharedOrderPayment extends Model {

    protected $fillable = [
        'pagamento',
        'descricao',
    ];

    /**
     * SharedOrderPayment can have many orders.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(){
        return $this->hasMany('App\Models\Order','payment_id');
    }

}
