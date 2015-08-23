<?php
/**
 * Created by PhpStorm.
 * User: luciano
 * Date: 22/08/15
 * Time: 12:15
 */

namespace App\Http\Controllers\OAuth2;

use App\Http\Controllers\Controller;
use App\Repositories\ProductRepository;

class DataController extends Controller {

    private $productRepository;

    /**
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository) {
        $this->productRepository = $productRepository;
    }


    /**
     * @return \App\Models\Product
     */
    public function productsCardapio() {
        return $this->productRepository->getProductsCardapio();
    }
}