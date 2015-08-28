<?php
/**
 * Created by PhpStorm.
 * User: luciano
 * Date: 27/08/15
 * Time: 20:52
 */

namespace App\Repositories;


use App\Models\Partner;

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
                if ( (strpos($item->status_list,'Ativado')!==false)&&($item->nome!='Luciano Porto') )
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

}