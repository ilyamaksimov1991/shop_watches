<?php
namespace app\Controllers\Admin;

use app\Models\CategoryModel;
use app\Models\OrderModel;
use app\Models\ProductModel;
use app\Models\UserModel;
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