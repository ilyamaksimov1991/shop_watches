<?php
namespace app\models;

/**
 * CurrencyModel
 *
 * @property int $id
 * @property string $title
 * @property string $code
 * @property string $symbol_left
 * @property string $symbol_right
 * @property int $value
 * @property int $base
 * @package app\models
 */
class CurrencyModel extends AppModel
{

    /**
     * Get currency
     *
     * @param $currency
     * @return NULL|\RedBeanPHP\OODBBean
     */
    public function getCurrency($array)
    {
        return \R::findOne('currency', $this->prepareQueryColumns($array), $this->getQueryValues($array));
    }

    /**
     * Get all currencies
     *
     * @return array
     */
    public function getAllCurrencies()
    {
        return \R::getAssoc("SELECT code, title, symbol_left, symbol_right, value, base, id FROM currency ORDER BY base DESC");

    }

    /**
     * @param int $id
     */
    public function deleteCurrency($id)
    {
        $currency = \R::load('currency', $id);
        \R::trash($currency);
    }

}