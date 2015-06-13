<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SharedOrderType extends Model {

    protected $fillable = [
        'tipo',
        'descricao',
    ];

    /**
     * SharedOrderType can have many orders.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(){
        return $this->hasMany('App\Models\Order','type_id');
    }

}
