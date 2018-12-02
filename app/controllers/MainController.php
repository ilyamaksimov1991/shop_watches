<?php
namespace app\controllers;

use app\models\BrandModel;
use app\models\ProductModel;

/**
 * Class MainController
 * @package app\controllers
 */
class MainController extends AppController
{

    public function indexAction()
    {

        $brands = (new BrandModel())->getBrand();
        $hits = (new ProductModel())->getProductHit();

        $this->setMeta('Страница index', 'desk', 'keywords');

        $this->render(compact('brands', 'hits'));
    }
}