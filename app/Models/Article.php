<?php namespace App\Models;

use Carbon\Carbon;
use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Article extends Model {

    use SoftDeletes;

    /**
     * Fillable fields for an Article.
     *
     * @var array
     */
	protected $fillable = [
        'title',
        'body',
        'published_at'
    ];

    /**
     * Additional fields to treat as Carbon instances.
     *
     * @var array
     */
    protected $dates = ['published_at'];

    /**
     * Scope queries to articles that have been published.
     * @param $query
     */
    public function scopePublished($query) {
        $query->where('published_at','<=',Carbon::now());
    }

    /**
     * Scope queries to articles that haven't been published.
     * @param $query
     */
    public function scopeUnpublished($query) {
        $query->where('published_at','>',Carbon::now());
    }

    /**
     * Set the published_at attribute.
     *
     * @param $date
     */
    public function setPublishedAtAttribute($date) {
        //$this->attributes['published_at'] = Carbon::createFromFormat('Y-m-d',$date);
        $this->attributes['published_at'] = Carbon::parse($date);
    }

    /**
     * Get the published_at attribute.
     *
     * @param $date
     * @return string
     */
    public function getPublishedAtAttribute($date) {
        return Carbon::parse($date)->format('Y-m-d');
    }

    /**
     * An article is owned by a user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {
        return $this->belongsTo('User');
    }

    /**
     * Get the tags associated with the given article.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags() {
        return $this->belongsToMany('\App\Models\Tag')->withTimestamps();
//        return $this->getCachedTags($cache);
    }

    /**
     * Get a list of tag ids associated with the current article.
     *
     * @return mixed
     */
    public function getTagListAttribute() {
        return $this->tags->lists('id');
    }

    public function getCachedLatestPublished(CacheRepository $cache){
        $cacheKey = 'getCachedLatestPublished'.md5($this->select(DB::raw('max(updated_at), count(id)'))->first()->toJson());
        if (!$cache->has($cacheKey)) {
            $cache->put($cacheKey, $this->latest('published_at')->published()->get(), Carbon::now()->addDay());
        }
        return $cache->get($cacheKey);
    }
}
