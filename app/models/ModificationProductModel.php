<?php
namespace app\models;

/**
 * ModificationProductModel
 *
 * @property int $id
 * @property int $price
 * @property string $title
 * @package app\models
 */
class ModificationProductModel extends AppModel
{
    /**
     * Get product
     *
     * @param $array
     * @return NULL|\RedBeanPHP\OODBBean
     */
    public function getProduct($array)
    {
        return \R::findOne('modification', $this->prepareQueryColumns($array), $this->getQueryValues($array));
    }

    /**
     * Get products modification
     *
     * @param array $array
     * @return array
     */
    public function getProducts($array)
    {
        return \R::findAll('modification', $this->prepareQueryColumns($array), $this->getQueryValues($array));
    }


}