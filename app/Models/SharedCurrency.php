<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SharedCurrency extends Model {

    /**
     * Fillable fields for a SharedCurrency.
     *
     * @var array
     */
    protected $fillable = [
        'nome_universal',
        'descricao',
    ];

    /**
     * SharedCurrency can have many itemOrders.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function itemOrders(){
        return $this->hasMany('App\Models\ItemOrder','currency_id');
    }

}
