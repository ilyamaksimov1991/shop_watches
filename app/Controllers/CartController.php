<?php
namespace app\Controllers;

use app\Models\Cart;
use app\Models\ModificationProductModel;
use app\Models\Order;
use app\Models\ProductModel;
use app\Models\User;

/**
 * Class CartController
 * @package app\controllers
 */
class CartController extends AppController
{

    public function addAction()
    {
        $id = !empty($_GET['id']) ? (int)$_GET['id'] : null;
        $qty = !empty($_GET['qty']) ? (int)$_GET['qty'] : null;
        $mod_id = !empty($_GET['mod']) ? (int)$_GET['mod'] : null;
        $mod = null;
        //from request

        if (!$id) {
            throw new \Exception('Нет такого продукта', 500);
        }

        $product = (new ProductModel())->getOneProduct(['id' => $id]);

        if (!$product) {
            return false; //return ????
        }
        if ($mod_id) {
            $mod = (new ModificationProductModel())->getProduct(['id' => $mod_id, 'product_id' => $id]);
        }

        /**
         * @var ProductModel $product
         */
        (new Cart())->addToCart($product, $qty, $mod);
        if ($this->isAjax()) {
            $this->loadView('cart_modal');
        }
        redirect();
    }

    public function showAction()
    {
        $this->loadView('cart_modal');
    }

    public function deleteAction()
    {
        $id = !empty($_GET['id']) ? $_GET['id'] : null;
        if (isset($_SESSION['cart'][$id])) {
            $cart = new Cart();
            $cart->deleteItem($id);
        }

        if ($this->isAjax()) {
            $this->loadView('cart_modal');
        }
        redirect();
    }

    public function viewAction()
    {
        $this->setMeta('Корзина');
    }

    public function clearAction()
    {
        (new Cart())->deleteSessionCart();
        $this->loadView('cart_modal');
    }


    public function checkoutAction()
    {
        if (!empty($_POST)) {
            // регистрация пользователя
            if (!User::isUserAuthorized()) {
                $user = new User();
                $data = $_POST;
                $user->load($data);
                if (!$user->validate($data) || !$user->checkUniqueLoginAndEmail()) {
                    $user->getErrors();
                    $_SESSION['form_data'] = $data;
                    redirect();
                } else {
                    $user->attributes['password'] = password_hash($user->attributes['password'], PASSWORD_DEFAULT);
                    if (!$user_id = $user->save('user')) {
                        $_SESSION['error'] = 'Ошибка!';
                        redirect();
                    }
                }
            }

            // сохранение заказа
            $data['user_id'] = isset($user_id) ? $user_id : $_SESSION['user']['id'];
            $data['note'] = !empty($_POST['note']) ? $_POST['note'] : '';
            $user_email = isset($_SESSION['user']['email']) ? $_SESSION['user']['email'] : $_POST['email'];

            $order = new Order();
            $order_id = $order->saveOrderInTheDatabase($data);
            $order->sendOrder($order_id, $user_email);
        }
        redirect();
    }

}