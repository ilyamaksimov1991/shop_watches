<?php
namespace app\Models;

/**
 * OrderModel
 *
 * @property int $id
 * @property int $user_id
 * @property int $status
 * @property string $date
 * @property string $update_at
 * @property string $currency
 * @property string $note
 *
 * @package app\models
 */
class OrderModel extends AppModel
{

    /**
     * @param int $orderId
     * @return NULL|\RedBeanPHP\OODBBean
     */
    public function getOrder($orderId)
    {
        return \R::findOne('order', $orderId);
    }

    /**
     * @param array $data
     * @return int|string
     */
    public function saveOrderAndGetRecordId($data)
    {
        /**
         * @var self $order
         */
        $order = \R::dispense('order');
        $order->user_id = $data['user_id'];
        $order->note = $data['note'];
        $order->currency = $_SESSION['cart.currency']['code'];
        return \R::store($order);
    }

    /**
     * Get orders where status = 0
     *
     * @return int
     */
    public function getCountUnprocessedOrders()
    {
        return \R::count('order', "status = '0'");

    }

    /**
     * Get full information about orders
     * Join information about the user
     * Join information about the order_product
     *
     * @param int $start
     * @param int $countOrderPerPage
     * @return array
     */
    public function getFullInformationAboutOrders($start, $countOrderPerPage)
    {
        return \R::getAll("SELECT `order`.`id`, `order`.`user_id`, `order`.`status`, `order`.`date`, 
                                                  `order`.`update_at`, `order`.`currency`, `user`.`name`, 
                                                   ROUND(SUM(`order_product`.`price`), 2) AS `sum` 
                                FROM `order`
                                JOIN `user` ON `order`.`user_id` = `user`.`id`
                                JOIN `order_product` ON `order`.`id` = `order_product`.`order_id`
                                GROUP BY `order`.`id` ORDER BY `order`.`status`, `order`.`id` 
                                LIMIT $start, $countOrderPerPage");
    }


    /**
     * Get full information about one order
     * Join information about the user
     * Join information about the order_product
     *
     * @param int $orderId
     * @return array
     */
    public function getFullInformationAboutOrder($orderId)
    {
        return \R::getRow("SELECT `order`.*, `user`.`name`, ROUND(SUM(`order_product`.`price`), 2) AS `sum` 
                                FROM `order`
                                JOIN `user` ON `order`.`user_id` = `user`.`id`
                                JOIN `order_product` ON `order`.`id` = `order_product`.`order_id`
                                WHERE `order`.`id` = ?
                                GROUP BY `order`.`id` ORDER BY `order`.`status`, `order`.`id` LIMIT 1", [$orderId]);
    }

    /**
     * @return int
     */
    public function getCountOrders()
    {
        return \R::count('order');
    }


    /**
     * @param int $orderId
     */
    public function deleteOrder($orderId)
    {
        $order = \R::load('order', $orderId);
        \R::trash($order);
    }

    /**
     * @param int $orderId
     * @param int $status
     */
    public function updateOrderStatus($orderId, $status)
    {
        /**
         * @var self $order
         */
        $order = \R::load('order', $orderId);
        $order->status = $status;
        $order->update_at = date("Y-m-d H:i:s");
        \R::store($order);

    }

    /**
     * @param int $userId
     * @return array
     */
    public function getFullInformationAboutTheUserOrder($userId)
    {
    return \R::getAll("SELECT `order`.`id`, `order`.`user_id`, `order`.`status`, `order`.`date`, `order`.`update_at`, `order`.`currency`,  
                                                                                               ROUND(SUM(`order_product`.`price`), 2) AS `sum` 
                            FROM `order`
                            JOIN `order_product` ON `order`.`id` = `order_product`.`order_id`
                            WHERE user_id = {$userId} 
                            GROUP BY `order`.`id` 
                            ORDER BY `order`.`status`, `order`.`id`");
    }


}