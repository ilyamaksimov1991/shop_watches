<?php
namespace app\Models;

use framework\App;

/**
 * Class Cart
 *
 * Template:
 *
 * Array
 * (
 * [1] => Array
 * (
 * [qty] => QTY
 * [name] => NAME
 * [price] => PRICE
 * [img] => IMG
 * )
 * [10] => Array
 * (
 * [qty] => QTY
 * [name] => NAME
 * [price] => PRICE
 * [img] => IMG
 * )
 * )
 * [qty] => QTY,
 * [sum] => SUM
 *
 * @package app\models
 */
class Cart extends AppModel
{

    /**
     * Add product to cart
     *
     * @param ProductModel $product
     * @param int $qty
     * @param ModificationProductModel $mod
     */
    public function addToCart($product, $qty = 1, $mod = null)
    {
        if (!isset($_SESSION['cart.currency'])) {
            $_SESSION['cart.currency'] = App::$app->getProperty('currency');
        }
        if ($mod) {
            $ID = "{$product->id}-{$mod->id}";
            $title = "{$product->title} ({$mod->title})";
            $price = $mod->price;
        } else {
            $ID = $product->id;
            $title = $product->title;
            $price = $product->price;
        }
        if (isset($_SESSION['cart'][$ID])) {
            $_SESSION['cart'][$ID]['qty'] += $qty;
        } else {
            $_SESSION['cart'][$ID] = [
                'qty' => $qty,
                'title' => $title,
                'alias' => $product->alias,
                'price' => $price * $_SESSION['cart.currency']['value'],
                'img' => $product->img,
            ];
        }
        $_SESSION['cart.qty'] = isset($_SESSION['cart.qty']) ? $_SESSION['cart.qty'] + $qty : $qty;
        $_SESSION['cart.sum'] = isset($_SESSION['cart.sum']) ? $_SESSION['cart.sum'] + $qty * ($price * $_SESSION['cart.currency']['value']) : $qty * ($price * $_SESSION['cart.currency']['value']);
    }


    /**
     * Delete item
     *
     * @param int $id
     */
    public function deleteItem($id)
    {
        $qtyMinus = $_SESSION['cart'][$id]['qty'];
        $sumMinus = $_SESSION['cart'][$id]['qty'] * $_SESSION['cart'][$id]['price'];
        $_SESSION['cart.qty'] -= $qtyMinus;
        $_SESSION['cart.sum'] -= $sumMinus;
        unset($_SESSION['cart'][$id]);
    }

    /**
     * Recalculation amounts when changing currency
     *
     * @param CurrencyModel $currency
     */
    public function RecalculationAmountsWhenChangingCurrency($currency)
    {
        if (isset($_SESSION['cart.currency'])) {
            if ($_SESSION['cart.currency']['base']) {
                $_SESSION['cart.sum'] *= $currency->value;
            } else {
                $_SESSION['cart.sum'] = $_SESSION['cart.sum'] / $_SESSION['cart.currency']['value'] * $currency->value;
            }
            foreach ($_SESSION['cart'] as $k => $v) {
                if ($_SESSION['cart.currency']['base']) {
                    $_SESSION['cart'][$k]['price'] *= $currency->value;
                } else {
                    $_SESSION['cart'][$k]['price'] = $_SESSION['cart'][$k]['price'] / $_SESSION['cart.currency']['value'] * $currency->value;
                }
            }
            foreach ($currency as $k => $v) {
                $_SESSION['cart.currency'][$k] = $v;
            }
        }
    }

    /**
     *  Delete session cart
     */
    public function deleteSessionCart()
    {
        unset($_SESSION['cart']);
        unset($_SESSION['cart.qty']);
        unset($_SESSION['cart.sum']);
        unset($_SESSION['cart.currency']);
    }

}