<?php
namespace app\controllers\admin;

use app\models\CategoryModel;
use app\models\OrderModel;
use app\models\ProductModel;
use app\models\UserModel;
use R;

/**
 * Class MainController
 * @package app\controllers\admin
 */
class MainController extends AppController {


    public function indexAction(){

        $countNewOrders = (new OrderModel())->getCountUnprocessedOrders();
        $countUsers = (new UserModel())->getCountUsers();
        $countProducts = (new ProductModel())->getCountProducts();
        $countCategories = (new CategoryModel())->getCountCategories();

        $this->setMeta('Панель управления');
        $this->render(compact('countNewOrders', 'countCategories', 'countProducts', 'countUsers'));
    }

}