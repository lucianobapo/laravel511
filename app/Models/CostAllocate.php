<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\MandanteTrait;

class CostAllocate extends Model {

    use SoftDeletes;
    use MandanteTrait;

    /**
     * Fillable fields for a CostAllocate.
     *
     * @var array
     */
    protected $fillable = [
        'mandante',
        'nome',
        'numero',
        'descricao',
    ];

    /**
     * CostAllocate can have many itemOrders.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function itemOrders(){
        return $this->hasMany('App\Models\ItemOrder','cost_id');
    }

    /**
     * Get the posted_at attribute.
     * @return string
     */
    public function getCostAttribute() {
//        dd($this);
//        $this->attributes['cost'] = $this->nome.' - '.$this->nome;
        return $this->attributes['numero'].' - '.$this->attributes['nome'];
    }

}
