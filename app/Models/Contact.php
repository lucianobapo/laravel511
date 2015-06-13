<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model {

    use SoftDeletes;

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
        return $this->belongsTo('Partner');
    }

}
