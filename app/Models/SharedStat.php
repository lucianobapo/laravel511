<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SharedStat extends Model {

    /**
     * Fillable fields for a SharedStat.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'descricao',
    ];

    /**
     * Get the orders associated with the given SharedStat.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orders() {
        return $this->belongsToMany('App\Models\Order')->withTimestamps();
    }

    /**
     * Get the partners associated with the given SharedStat.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function partners() {
        return $this->belongsToMany('App\Models\Partner')->withTimestamps();
    }

    /**
     * Get the products associated with the given SharedStat.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products() {
        return $this->belongsToMany('App\Models\Product')->withTimestamps();
    }

}
