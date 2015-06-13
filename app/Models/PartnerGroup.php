<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerGroup extends Model {

    /**
     * Fillable fields for a PartnerGroup.
     *
     * @var array
     */
    protected $fillable = [
        'mandante',
        'grupo',
    ];

    /**
     * Get the products associated with the given PartnerGroup.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function partners() {
        return $this->belongsToMany('Partner')->withTimestamps();
    }



}
