<?php namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\MandanteTrait;
use Illuminate\Cache\Repository as CacheRepository;
use Illuminate\Support\Facades\DB;

class Order extends Model {

    use SoftDeletes;
    use MandanteTrait;

    /**
     * Fillable fields for an Order.
     *
     * @var array
     */
    protected $fillable = [
        'mandante',
        'partner_id',
        'address_id',
        'currency_id',
        'type_id',
        'payment_id',
        'posted_at',
        'valor_total',
        'desconto_total',
        'troco',
        'descricao',
        'referencia',
        'obsevacao'
    ];

    /**
     * Additional fields to treat as Carbon instances.
     *
     * @var array
     */
    protected $dates = ['posted_at'];

    /**
     * An Order belongs to a Partner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function partner() {
        return $this->belongsTo('App\Models\Partner');
    }

    /**
     * An Order belongs to a Address.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function address() {
        return $this->belongsTo('App\Models\Address');
    }

    /**
     * An Order belongs to a SharedCurrency.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency() {
        return $this->belongsTo('App\Models\SharedCurrency');
    }

    /**
     * An Order belongs to an Order Type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type() {
        return $this->belongsTo('App\Models\SharedOrderType','type_id');
    }

    /**
     * An Order belongs to an Order Payment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payment() {
        return $this->belongsTo('App\Models\SharedOrderPayment','payment_id');
    }


    /**
     * Get the status associated with the given order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function status() {
        return $this->belongsToMany('App\Models\SharedStat')->withTimestamps();
    }

    public function getStatusListAttribute(){
        $status = $this->status->toArray();
        $lista = '';
        foreach($status as $stat){
            $lista = $lista . $stat['descricao'].', ';
        }
        return substr($lista, 0, -2);
    }

    /**
     * Order can have many items.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderItems(){
        return $this->hasMany('App\Models\ItemOrder');
//        return $this->hasMany('ItemOrder')->with('order', 'product', 'cost', 'currency');
    }

    /**
     * Order can have many confirmations.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function confirmations(){
        return $this->hasMany('App\Models\OrderConfirmation');
    }

    /**
     * Order can have many attachments.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments(){
        return $this->hasMany('App\Models\Attachment');
    }

    public function hasConfirmation($type){
        $confirmations = $this->confirmations->toArray();
        foreach($confirmations as $confirmation)
            if ($confirmation['type']==$type) return true;
        return false;
    }

    public function getKmInicialAttribute(){
        $confirmations = $this->confirmations->toArray();
        foreach($confirmations as $confirmation)
            if ( ($confirmation['type']=='entregando') && (!empty($confirmation['message'])) )
                return $confirmation['message']*1;
        return 0;
    }

    public function getKmFinalAttribute(){
        $confirmations = $this->confirmations->toArray();
        foreach($confirmations as $confirmation)
            if ( ($confirmation['type']=='entregue') && (!empty($confirmation['message'])) )
                return $confirmation['message']*1;
        return 0;
    }

    /**
     * Order can have many items.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
//    public function cachedOrderItems(CacheRepository $cache){
////        dd($this->hasMany('ItemOrder')->with('order', 'product')->get());
////        dd(ItemOrder::select(DB::raw('max(updated_at), count(id)'))->first()->toArray());
//        $cacheKey = 'cachedOrderItems'.md5(ItemOrder::select(DB::raw('max(updated_at), count(id)'))->first()->toJson());
//        if (!$cache->has($cacheKey)) {
//            $result = $this->hasMany('ItemOrder')->with('order', 'product')->get();
//            $cache->put($cacheKey, $result, Carbon::now()->addDay());
//        }
//        return $cache->get($cacheKey);
////        return $this->hasMany('ItemOrder')->with('order', 'product')->get();
//    }

    /**
     * Set the posted_at attribute.
     *
     * @param $date
     */
    public function setPostedAtAttribute($date) {
        //$this->attributes['published_at'] = Carbon::createFromFormat('Y-m-d',$date);
        $this->attributes['posted_at'] = Carbon::parse($date);
    }

    /**
     * Get the posted_at attribute.
     *
     * @return string
     */
    public function getPostedAtAttribute() {
            return Carbon::parse($this->attributes['posted_at'])->format('d/m/Y H:i');
    }

    /**
     * Get the posted_at attribute.
     *
     * @return string
     */
    public function getPostedAtDataAttribute() {
        return Carbon::parse($this->attributes['posted_at'])->format('d/m/Y');
    }

    /**
     * Get the posted_at attribute.
     * @return string
     */
    public function getPostedAtForFieldAttribute() {
            return Carbon::parse($this->attributes['posted_at'])->format('Y-m-d\TH:i');
    }

    /**
     * Get the posted_at attribute.
     * @return string
     */
    public function getPostedAtTimestampAttribute() {
            return Carbon::parse($this->attributes['posted_at'])->timestamp;
    }

    /**
     * Get the posted_at attribute.
     * @return string
     */
    public function getPostedAtCarbonAttribute() {
            return Carbon::parse($this->attributes['posted_at']);
    }

    /**
     * Get the posted_at attribute.
     * @return string
     */
    public function getTodayAttribute() {
        return Carbon::now()->format('Y-m-d\TH:i');
    }



//    public function cachedAll(CacheRepository $cache){
////        dd($this->with('partner', 'currency', 'type', 'payment', 'status', 'orderItems')->get());
////        return $this->with('partner', 'currency', 'type', 'payment', 'status', 'orderItems')->get();
////        dd(Carbon::now()->addDay());
////        dd($this->select(DB::raw('max(updated_at), count(id)'))->first()->toArray());
//        $cacheKey = 'cachedAll23'.md5($this->select(DB::raw('max(updated_at), count(id)'))->first()->toJson());
//        if (!$cache->has($cacheKey)) {
//            $result = $this->with('partner', 'currency', 'type', 'payment', 'status', 'orderItems')->get();
////            $cache->put($cacheKey, $result, Carbon::now()->addDay());
//            $cache->put($cacheKey, $result, config('cache.queryCacheTimeMinutes'));
//        }
//        return $cache->get($cacheKey);
//    }
}
