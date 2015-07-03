<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\MandanteTrait;

class Contact extends Model {

    use SoftDeletes;
    use MandanteTrait;

    /**
     * Fillable fields for a Contact.
     *
     * @var array
     */
    protected $fillable = [
        'mandante',
        'partner_id',
        'contact_type',
        'contact_data',
    ];

    /**
     * A Contact is owned by a partner.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partner(){
        return $this->belongsTo('App\Models\Partner');
    }

}
