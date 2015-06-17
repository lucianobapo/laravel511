<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderConfirmation extends Model
{
    use SoftDeletes;

    /**
     * Fillable fields for an OrderConfirmation.
     *
     * @var array
     */
    protected $fillable = [
        'mandante',
        'order_id',
        'type',
    ];

    /**
     * An OrderConfirmation belongs to an Order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order() {
        return $this->belongsTo('App\Models\Order');
    }
}
