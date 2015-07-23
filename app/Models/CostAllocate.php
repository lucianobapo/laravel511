<?php namespace App\Models;

use App\Models\Scopes\GridSortingTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\MandanteTrait;

class CostAllocate extends Model {

    use SoftDeletes;
    use MandanteTrait;
    use GridSortingTrait;

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
    public function getCostListAttribute() {
        return $this->attributes['numero'].' - '.$this->attributes['descricao'];
    }

}
