<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\MandanteTrait;

class Document extends Model {

    use SoftDeletes;
    use MandanteTrait;

    /**
     * Fillable fields for a Document.
     *
     * @var array
     */
    protected $fillable = [
        'mandante',
        'partner_id',
        'document_type',
        'document_data',
    ];

    /**
     * A Document is owned by a partner.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partner(){
        return $this->belongsTo('App\Models\Partner');
    }
}
