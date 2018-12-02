<?php
namespace app\controllers\admin;

use app\models\OrderModel;
use app\models\OrderProductModel;
use framework\App;
use framework\libs\Pagination;

/**
 * Class OrderController
 * @package app\controllers\admin
 */
class OrderController extends AppController
{

    public function indexAction()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $orders = new OrderModel();


        $countOrderPerPage = App::$app->getProperty('pagination_admin_orders');

        $count = $orders->getCountOrders();
        $pagination = new Pagination($page, $countOrderPerPage, $count);
        $start = $pagination->getStart();

        $orders = $orders->getFullInformationAboutOrders($start, $countOrderPerPage);

        $this->setMeta('Список заказов');
        $this->render(compact('orders', 'pagination', 'count'));
    }

    public function viewAction()
    {
        $orderId = $_GET['id'];
        if (!$orderId) {
            throw new \Exception('Категории не существует', 404);
        }

        $order = (new OrderModel())->getFullInformationAboutOrder($orderId);
        if (!$order) {
            throw new \Exception('Страница не найдена', 404);
        }
        $orderProducts = (new OrderProductModel())->getOrderProducts($orderId);

        $this->setMeta("Заказ №{$orderId}");
        $this->render(compact('order', 'orderProducts'));
    }

    public function changeAction()
    {
        $orderId = $_GET['id'];
        if (!$orderId) {
            throw new \Exception('Категории не существует', 404);
        }

        $status = !empty($_GET['status']) ? '1' : '0';

        $orderModel = new OrderModel();
        $order = $orderModel->getOrder($orderId);
        if (!$order) {
            throw new \Exception('Заказ не найден', 404);
        }
        $orderModel->updateOrderStatus($orderId, $status);


        $_SESSION['success'] = 'Изменения сохранены';
        redirect();
    }

    public function deleteAction()
    {
        $orderId = $_GET['id'];
        if (!$orderId) {
            throw new \Exception('Категории не существует', 404);
        }

        (new OrderModel())->deleteOrder($orderId);

        $_SESSION['success'] = 'Заказ удален';
        redirect(ADMIN . '/order');
    }

}