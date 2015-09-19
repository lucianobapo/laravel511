<?php
/**
 * Created by PhpStorm.
 * User: luciano
 * Date: 27/08/15
 * Time: 20:52
 */

namespace App\Repositories;

use App\Models\Partner;
use Carbon\Carbon;
use Illuminate\Cache\Repository as CacheRepository;

class PartnerRepository {
    /**
     * @var CacheRepository
     */
    private $cache;
    private $partnersCacheKey;

    private $partner;

    public function __construct(Partner $partner, CacheRepository $cache) {
        $this->partner = $partner;
        $this->cache = $cache;
        $this->partnersCacheKey = getTableCacheKey('partners');
    }

    public function getPartnersActivated() {
        return $this->partner
            ->with('orders', 'orders.type', 'addresses')
//            ->orderBy('posted_at', 'desc' )
            ->select('partners.*')
            ->join('partner_shared_stat', 'partners.id', '=', 'partner_shared_stat.partner_id')
            ->join('shared_stats', 'partner_shared_stat.shared_stat_id', '=', 'shared_stats.id')
            ->where('shared_stats.status', '=', 'ativado')
            ->orderBy('nome', 'asc' )
            ->get();
//            ->filter(function($item) {
//                if ( (strpos($item->status_list,'Ativado')!==false)&&($item->nome!='Luciano Porto')&&($item->nome!='Amanda Valeria') )
//                    return $item;
//            });
    }

    public function getPartnersActivatedWithOrder() {
        return $this->getPartnersActivated()
            ->filter(function($item) {
                if (count($item->orders)>0)
                    return $item;
            });
    }

    public function getLevantamentoDeParceiros() {
        $usuariosFiltrados = $this->getPartnersActivatedWithOrder();

        foreach ($usuariosFiltrados as $partner){
            if (count($partner->orders)>0) {
                $usuarioNovo = false;
                foreach ($partner->orders as $order) {
                    if ($order->type->tipo=='ordemVenda') {
                        $usuarios[$partner->nome] = isset($usuarios[$partner->nome])?$usuarios[$partner->nome]+1:1;
                        $usuariosValor[$partner->nome] = isset($usuariosValor[$partner->nome])?$usuariosValor[$partner->nome]+$order->valor_total:$order->valor_total;

                        $usuariosAntigos[$partner->nome] = isset($usuariosAntigos[$partner->nome])?$usuariosAntigos[$partner->nome]+1:1;
                        $usuariosAntigosValor[$partner->nome] = isset($usuariosAntigosValor[$partner->nome])?$usuariosAntigosValor[$partner->nome]+$order->valor_total:$order->valor_total;

                        if ($order->posted_at_carbon>Carbon::now()->subMonth()){
                            $usuarioNovo = true;
                        }
                    }
                }
                if ($usuarioNovo){
                    unset($usuariosAntigos[$partner->nome]);
                    unset($usuariosAntigosValor[$partner->nome]);
                }
            }
        }

        arsort($usuarios);
        arsort($usuariosValor);

        arsort($usuariosAntigos);
        arsort($usuariosAntigosValor);

        return [
            'ordensUsuarios'=>$usuarios,
            'somaOrdensUsuarios'=>array_sum($usuarios),

            'ordensUsuariosValor'=>$usuariosValor,
            'somaOrdensUsuariosValor'=>array_sum($usuariosValor),

            'ordensUsuariosAntigos'=>$usuariosAntigos,
            'somaOrdensUsuariosAntigos'=>array_sum($usuariosAntigos),

            'ordensUsuariosAntigosValor'=>$usuariosAntigosValor,
            'somaOrdensUsuariosAntigosValor'=>array_sum($usuariosAntigosValor),
        ];
    }

    private function getPartnersActivatedSelectList() {
        return [''=>''] + $this->getPartnersActivated()
            ->lists('nome','id')
            ->toArray();
    }

    /**
     * @return array
     */
    public function getCachedPartnersActivatedSelectList() {
        $tag = 'PartnersActivatedSelectList';
        if ($this->cache->tags($tag)->has($this->partnersCacheKey)) {
            return $this->cache->tags($tag)->get($this->partnersCacheKey);
        } else {
            $cacheContent = $this->getPartnersActivatedSelectList();
            $this->cache->tags($tag)->flush();
            $this->cache->tags($tag)->forever($this->partnersCacheKey,$cacheContent);
            return $cacheContent;
        }
    }

    /**
     * @return array
     */
    public function getCachedPartnersActivated() {
        $tag = 'PartnersActivated';
        if ($this->cache->tags($tag)->has($this->partnersCacheKey)) {
            return $this->cache->tags($tag)->get($this->partnersCacheKey);
        } else {
            $cacheContent = $this->getPartnersActivated();
            $this->cache->tags($tag)->flush();
            $this->cache->tags($tag)->forever($this->partnersCacheKey,$cacheContent);
            return $cacheContent;
        }
    }

}