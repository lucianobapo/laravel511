<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\MandanteTrait;

class ItemOrder extends Model {

    use SoftDeletes;
    use MandanteTrait;

    /**
     * Fillable fields for an Item Order.
     *
     * @var array
     */
    protected $fillable = [
        'mandante',
        'order_id',
        'cost_id',
        'product_id',
        'currency_id',
        'quantidade',
        'valor_unitario',
        'desconto_unitario',
        'descricao',
    ];

    /**
     * An Item Order belongs to an Order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order() {
        return $this->belongsTo('App\Models\Order');
    }

    /**
     * An Item Order belongs to a Product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product() {
        return $this->belongsTo('App\Models\Product');
    }

    /**
     * An Item Order belongs to an CostAllocate.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cost() {
        return $this->belongsTo('App\Models\CostAllocate');
    }

    /**
     * An Item Order belongs to an SharedCurrency.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency() {
        return $this->belongsTo('App\Models\SharedCurrency');
    }

}
