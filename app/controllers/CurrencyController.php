<?php
namespace app\controllers;

use app\models\Cart;
use app\models\CurrencyModel;

/**
 * Class CurrencyController
 * @package app\controllers
 */
class CurrencyController extends AppController
{
    public function changeAction()
    {
        $currency = !empty($_GET['curr']) ? $_GET['curr'] : null;
        if (isset($currency)) {
            $curr = (new CurrencyModel())->getCurrency(['code' => $currency]);
            if (!empty($curr)) {
                setcookie('currency', $currency, time() + 3600 * 24 * 7, '/');
                (new Cart())->RecalculationAmountsWhenChangingCurrency($curr);
            }
        }

        redirect();
    }

}