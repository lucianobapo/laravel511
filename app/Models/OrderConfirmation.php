<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'message',
    ];

    /**
     * Get the posted_at attribute.
     *
     * @param $date
     * @return string
     */
    public function getCreatedAtAttribute($date) {
        return Carbon::parse($date)->format('d/m/Y H:i');
    }

    /**
     * An OrderConfirmation belongs to an Order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order() {
        return $this->belongsTo('App\Models\Order');
    }
}
