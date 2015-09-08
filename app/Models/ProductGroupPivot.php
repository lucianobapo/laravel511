<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductGroupPivot extends Pivot {

    /**
     * Get the products associated with the given ProductGroup.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
//    public function products() {
//        return $this->belongsToMany('App\Models\Product')->withTimestamps();
//    }

    public function products() {
        return $this->belongsTo('App\Models\Product');
    }

    public function groups() {
        return $this->belongsTo('App\Models\ProductGroup');
    }

}
