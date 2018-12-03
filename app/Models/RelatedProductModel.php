<?php
namespace app\Models;

/**
 * Class RelatedProductModel
 *
 * @property int $product_id
 * @property int $related_id
 * @package app\models
 */
class RelatedProductModel extends AppModel
{

    /**
     * Get related products
     *
     * @param int $id
     * @return array
     */
    public function getRelatedProducts($id)
    {
        return \R::getAll("SELECT * FROM related_product 
                                JOIN product ON product.id = related_product.related_id 
                                WHERE related_product.product_id = ?", [$id]);
    }


    /**
     * @param string $column
     * @param array $product
     * @return array
     */
    public function getColumn($column, array $product)
    {
        return \R::getCol("SELECT {$column} FROM related_product WHERE {$this->prepareQueryColumns($product)}", $this->getQueryValues($product));
    }

    /**
     * @param array $array
     * @return int
     */
    public function delete($array)
    {
        return \R::exec("DELETE FROM related_product WHERE {$this->prepareQueryColumns($array)}", $this->getQueryValues($array));
    }


    /**
     * @param string $sql
     * @return int
     */
    public function addRelatedProduct($sql)
    {
        return \R::exec("INSERT INTO related_product (product_id, related_id) VALUES $sql");
    }
}