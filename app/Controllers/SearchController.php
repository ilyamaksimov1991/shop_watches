<?php
namespace app\Controllers;

use app\Models\ProductModel;

/**
 * Class SearchController
 * @package app\controllers
 */
class SearchController extends AppController{


    public function typeaheadAction(){
        if($this->isAjax()){
            $query = !empty(trim($_GET['query'])) ? trim($_GET['query']) : null;
            if($query){
                $products = (new ProductModel())->getProductsFromTheSearchQuery($query);
                echo json_encode($products);
            }
        }
        die;
    }

    public function indexAction(){
        $search = !empty(trim($_GET['search'])) ? trim($_GET['search']) : null;
        if($search){
            $products = (new ProductModel())->getProductLike($search);
        }
        $this->setMeta('Поиск по: ' . h($search));
        $this->render(compact('products', 'search'));
    }

}