<?php
namespace app\models;

/**
 * Class OrderProductModel
 *
 * @property int $id
 * @property int $order_id
 * @property int $product_id
 * @property int $qty
 * @property string $title
 * @property int $price
 *
 * @package app\models
 */
class OrderProductModel extends AppModel
{

    /**
     * @param string $sql
     * @return int
     */
    public function saveOrderProduct($sql)
    {
        return \R::exec("INSERT INTO order_product (order_id, product_id, qty, title, price) VALUES $sql");
    }

    /**
     * @param string $orderId
     * @return array
     */
    public function getOrderProducts($orderId)
    {
        return \R::findAll('order_product', "order_id = ?", [$orderId]);
    }

}