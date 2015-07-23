<?php namespace App\Models;

use App\Models\Scopes\GridSortingTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\MandanteTrait;

class Document extends Model {

    use SoftDeletes;
    use MandanteTrait;
    use GridSortingTrait;

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

    public function getDocumentTypeNameAttribute(){
        if (isset($this->attributes['document_type']))
            return config('delivery.document_types')[$this->attributes['document_type']];
    }
}
