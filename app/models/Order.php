<?php
namespace app\models;

use framework\App;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

/**
 * Class Order
 * @package app\models
 */
class Order extends AppModel
{

    /**
     * @param array $data
     * @return int|string
     */
    public function saveOrderInTheDatabase($data)
    {
        $orderId = (new OrderModel())->saveOrderAndGetRecordId($data);
        $sql_part = '';
        foreach ($_SESSION['cart'] as $product_id => $product) {
            $product_id = (int)$product_id;
            $sql_part .= "($orderId, $product_id, {$product['qty']}, '{$product['title']}', {$product['price']}),";
        }
        $sql_part = rtrim($sql_part, ',');
        (new OrderProductModel())->saveOrderProduct($sql_part);
        return $orderId;
    }

    /**
     * Notification of the customer and the admin about the new order
     * Delete session cart
     * Notify success order
     *
     * @param int $orderId
     * @param string $userEmail
     */
    public function sendOrder($orderId, $userEmail)
    {
        $mailer = $this->createMailer();

        $body = $this->getBody();

        $this->sendEmailCustomerAndAdmin($mailer,$body, $orderId, $userEmail);

        (new Cart())->deleteSessionCart();
        $_SESSION['success'] = 'Спасибо за Ваш заказ. В ближайшее время с Вами свяжется менеджер для согласования заказа';
    }

    /**
     * @return Swift_Mailer
     */
    private function createMailer()
    {
        $transport = (new Swift_SmtpTransport(
            App::$app->getProperty('smtp_host'),
            App::$app->getProperty('smtp_port'),
            App::$app->getProperty('smtp_protocol')))
            ->setUsername(App::$app->getProperty('smtp_login'))
            ->setPassword(App::$app->getProperty('smtp_password'));

        return new Swift_Mailer($transport);
    }

    /**
     * @return string
     */
    private function getBody()
    {
        ob_start();
        require APP . '/views/mail/mail_order.php';
        return ob_get_clean();

    }

    /**
     * @param Swift_Mailer $mailer
     * @param string $body
     * @param string $orderId
     * @param string $userEmail
     */
    private function sendEmailCustomerAndAdmin($mailer, $body, $orderId, $userEmail)
    {
        $message_client = (new Swift_Message("Вы совершили заказ №{$orderId} на сайте " . App::$app->getProperty('shop_name')))
            ->setFrom([App::$app->getProperty('smtp_login') => App::$app->getProperty('shop_name')])
            ->setTo($userEmail)
            ->setBody($body, 'text/html');

        $message_admin = (new Swift_Message("Сделан заказ №{$orderId}"))
            ->setFrom([App::$app->getProperty('smtp_login') => App::$app->getProperty('shop_name')])
            ->setTo(App::$app->getProperty('admin_email'))
            ->setBody($body, 'text/html');

        $mailer->send($message_client);
        $mailer->send($message_admin);
    }

}