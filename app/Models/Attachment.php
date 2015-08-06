<?php

namespace App\Models;

use App\Models\Scopes\MandanteTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attachment extends Model
{
    use SoftDeletes;
    use MandanteTrait;

    /**
     * Fillable fields for a Product.
     *
     * @var array
     */
    protected $fillable = [
        'mandante',
        'order_id',
        'file',
    ];

    /**
     * An Attachment belongs to an Order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order() {
        return $this->belongsTo('App\Models\Order');
    }
}
