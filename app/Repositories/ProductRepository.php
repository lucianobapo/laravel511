<?php
/**
 * Created by PhpStorm.
 * User: luciano
 * Date: 22/08/15
 * Time: 13:13
 */

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Cache\Repository as CacheRepository;

class ProductRepository {

    /**
     * @var CacheRepository
     */
    private $cache;
    private $productsCacheKey;

    /**
     * @var Product $product
     */
    private $product;
//    private $productsGetWiths;
    private $productsGetWithsDelivery;

    /**
     * @param Product $product
     */
    public function __construct(Product $product, CacheRepository $cache) {
        $this->product = $product;
        $this->cache = $cache;
        $this->productsCacheKey = getTableCacheKey('products');

//        $this->productsGetWiths = $this->product
//            ->with('status','groups');
        $this->productsGetWithsDelivery = $this->product
            ->select('products.*')
            ->join('product_shared_stat', 'products.id', '=', 'product_shared_stat.product_id')
            ->join('shared_stats', 'product_shared_stat.shared_stat_id', '=', 'shared_stats.id')

            ->join('product_product_group', 'products.id', '=', 'product_product_group.product_id')
            ->join('product_groups', 'product_product_group.product_group_id', '=', 'product_groups.id')

            ->where('shared_stats.status', '=', 'ativado')
            ->where('product_groups.grupo', '=', 'Delivery')

            ->with('status','groups');
    }

    private function toCollection() {
        if (get_class($this->productsGetWithsDelivery)=='Illuminate\Database\Eloquent\Builder') {
            $this->productsGetWithsDelivery = $this->productsGetWithsDelivery->get();
        }
    }

    public function getProductsDelivery($estoque) {
        $this->toCollection();
        return $this->productsGetWithsDelivery
            ->filter(function($item) use ($estoque) {
                $return = false;
                foreach ($item->groups as $group) {
                    if($group->id==1) $return = true;
                }

                if ( (isset($estoque[$item->id]))&&($estoque[$item->id]>0) )
                    $return = true;

                if ($return) return $item;
            });
    }

    public function getProductsCategoria($estoque, $categoria) {
        return $this->getProductsDelivery($estoque)
            ->filter(function($item) use ($categoria) {
                if ($item->categoria_list==$categoria) return $item;
            });
    }

    private function getProductActivated() {
        return $this->product
            ->with('groups')
            ->select('products.*')
            ->join('product_shared_stat', 'products.id', '=', 'product_shared_stat.product_id')
            ->join('shared_stats', 'product_shared_stat.shared_stat_id', '=', 'shared_stats.id')
            ->where('shared_stats.status', '=', 'ativado');
    }

    public function getProductActivatedEstoque() {
        return $this->getProductActivated()
            ->where('products.estoque', 1);
    }

    /**
     * @return array
     */
    public function getCachedProductActivated() {
        $tag = 'ProductActivated';
        if ($this->cache->tags($tag)->has($this->productsCacheKey)) {
            return $this->cache->tags($tag)->get($this->productsCacheKey);
        } else {
            $cacheContent = $this->getProductActivated()->get();
            $this->cache->tags($tag)->flush();
            $this->cache->tags($tag)->forever($this->productsCacheKey,$cacheContent);
            return $cacheContent;
        }
    }

    /**
     * @return array
     */
    public function getCachedProductActivatedSelectList() {
        $tag = 'ProductActivatedSelectList';
        if ($this->cache->tags($tag)->has($this->productsCacheKey)) {
            return $this->cache->tags($tag)->get($this->productsCacheKey);
        } else {
            $cacheContent = $this->getProductActivatedSelectList();
            $this->cache->tags($tag)->flush();
            $this->cache->tags($tag)->forever($this->productsCacheKey,$cacheContent);
            return $cacheContent;
        }
    }

    private function getProductActivatedSelectList() {
        return [''=>''] + $this->getProductActivated()
            ->get()
            ->lists('nome','id')
            ->toArray();
    }

}