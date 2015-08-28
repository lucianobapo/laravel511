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

class PartnerRepository {
    private $partner;

    public function __construct(Partner $partner) {
        $this->partner = $partner;
    }

    public function getPartnersActivated() {
        return $this->partner
            ->with('status','orders','orders.type')
//            ->orderBy('posted_at', 'desc' )
            ->orderBy('id', 'desc' )
            ->get()
            ->filter(function($item) {
                if ( (strpos($item->status_list,'Ativado')!==false)&&($item->nome!='Luciano Porto')&&($item->nome!='Amanda Valeria') )
                    return $item;
            });
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

}