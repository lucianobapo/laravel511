<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\MandanteTrait;

class OrderConfirmation extends Model
{
    use SoftDeletes;
    use MandanteTrait;

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
        'posted_at',
    ];

    /**
     * Additional fields to treat as Carbon instances.
     *
     * @var array
     */
    protected $dates = ['posted_at'];

    /**
     * Set the posted_at attribute.
     *
     * @param $date
     */
    public function setPostedAtAttribute($date) {
        $this->attributes['posted_at'] = Carbon::parse($date);
    }

    /**
     * Get the posted_at attribute.
     *
     * @param $date
     * @return string
     */
    public function getPostedAtAttribute($date) {
        return Carbon::parse($date)->format('d/m/Y H:i');
    }

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
