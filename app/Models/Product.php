<?php namespace App\Models;

use App\Models\Scopes\GridSortingTrait;
use App\Models\Scopes\SyncItemsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\MandanteTrait;
use Illuminate\Support\Facades\Auth;

class Product extends Model {

    use SoftDeletes;
    use MandanteTrait;
    use GridSortingTrait;
    use SyncItemsTrait;

    /**
     * Fillable fields for a Product.
     *
     * @var array
     */
    protected $fillable = [
        'mandante',
        'cost_id',
        'nome',
        'imagem',
        'cod_fiscal',
        'cod_barra',
        'promocao',
        'estoque',
        'estoque_minimo',
        'valorUnitVenda',
        'valorUnitVendaPromocao',
        'valorUnitCompra',
    ];

    /**
     * @var String
     */
    private $filtro;

    /**
     * A Product belongs to a CostAllocate.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cost() {
        return $this->belongsTo('App\Models\CostAllocate');
    }

    /**
     * Partner can have many orders.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function itemOrders(){
        return $this->hasMany('App\Models\ItemOrder');
    }

    /**
     * Get the groups associated with the given product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups() {
        return $this->belongsToMany('App\Models\ProductGroup')->withTimestamps();
    }

    public function getGroupListAttribute(){
        $groups = $this->groups->toArray();
        $lista = '';
        foreach($groups as $group){
            $lista = $lista . $group['grupo'].', ';
        }
        return substr($lista, 0, -2);
    }

    public function getCategoriaListAttribute(){
        $groups = $this->groups->toArray();
        $lista = '';
        foreach($groups as $group){
//            dd(strpos($group['grupo'],'Categoria:'));
            if (strpos($group['grupo'],'Categoria:')!==false)
            $lista = $lista . substr($group['grupo'],11).', ';
        }
        return substr($lista, 0, -2);
    }

//    public function filtraCachedGroup($filtro, CacheRepository $cache) {
//        $this->filtro=$filtro;
//        $cacheKey = 'getCachedFiltraGrupo234'.str_slug($filtro).md5($this->select(DB::raw('max(updated_at), count(id)'))->first()->toJson());
//        if (!$cache->has($cacheKey)) {
//            $filtered = $this->orderBy('promocao', 'desc' )->orderBy('nome', 'asc' )->get()->filter(function($item) {
//                $found = false;
//                foreach ($item->groups->toArray() as $group) {
//                    if (array_search($this->filtro,$group)) $found = true;
//                }
//                if ($found) return $item;
//            });
//            $cache->put($cacheKey, $filtered, config('cache.queryCacheTimeMinutes'));
//        }
//        return $cache->get($cacheKey);
//    }

    public function filtraGroup($filtro) {
        $this->filtro=$filtro;
        return $this->all()->filter(function($item) {
            $found = false;
            foreach ($item->groups->toArray() as $group) {
                if (array_search($this->filtro,$group)) $found = true;
            }
            if ($found) return $item;
        });
    }

    public function filtraStatus($filtro) {
        $this->filtro=$filtro;
        return $this->all()->filter(function($item) {
            $found = false;
            foreach ($item->status->toArray() as $group) {
                if (array_search($this->filtro,$group)) $found = true;
            }
            if ($found) return $item;
        });
    }

//    public function getCachedLatestPublished(CacheRepository $cache){
//        $cacheKey = 'getCachedLatestPublished'.md5($this->select(DB::raw('max(updated_at), count(id)'))->first()->toJson());
//        if (!$cache->has($cacheKey)) {
//            $cache->put($cacheKey, $this->latest('published_at')->published()->get(), Carbon::now()->addDay());
//        }
//        return $cache->get($cacheKey);
//    }

    /**
     * Get the status associated with the given Product.
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

    public function checkStatus(array $lista, $status){
        foreach($lista as $item) if ($item['status']==$status) return true;
        return false;
    }

    public function checkGroup(array $lista, $group){
        foreach($lista as $item) if ($item['grupo']==$group) return true;
        return false;
    }

    public function getProductListAttribute(){
        return $this->with('status')
            ->orderBy('nome', 'asc')
            ->get()
            ->filter(function($item) {
                if ( (strpos($item->status_list,'Ativado')!==false) || (Auth::user()->role->name==config('delivery.rootRole')) )
                    return $item;
            });
    }
    public function getProductSelectListAttribute(){
        return [''=>''] + $this->product_list
            ->lists('nome','id')
            ->toArray();
    }

}
