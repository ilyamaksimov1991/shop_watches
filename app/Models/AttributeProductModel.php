<?php
namespace app\Models;

/**
 * AttributeProductModel
 *
 * @property int $attr_id
 * @property int $product_id
 *
 * @package app\models
 */
class AttributeProductModel extends AppModel
{
    /**
     * @param int $id
     * @return int
     */
    public function delete($array)
    {
        return  \R::exec("DELETE FROM attribute_product WHERE {$this->prepareQueryColumns($array)}", $this->getQueryValues($array));
    }

    /**
     * @param string $column
     * @param array $product
     * @return array
     */
    public function getColumn($column, array $product)
    {
        return \R::getCol("SELECT {$column} FROM attribute_product WHERE {$this->prepareQueryColumns($product)}", $this->getQueryValues($product));
    }

    /**
     * @param string $sql
     * @return int
     */
    public function addAttibuteProduct($sql)
    {
        return \R::exec("INSERT INTO attribute_product (attr_id, product_id) VALUES $sql");
    }
}