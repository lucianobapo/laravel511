<?php namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Support\Facades\DB;

class Tag extends Model {

    /**
     * Fillable fields for a tag.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get the articles associated with the given tag.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
	public function articles() {
	    return $this->belongsToMany('Article');
	}

    public function getCachedLists(CacheRepository $cache, $key, $value){
        $cacheKey = 'getCachedLists'.md5($this->select(DB::raw('max(updated_at), count(id)'))->first()->toJson());
        if (!$cache->has($cacheKey)) {
            $cache->put($cacheKey, $this->lists($key, $value), Carbon::now()->addDay());
        }
        return $cache->get($cacheKey);
    }

}
