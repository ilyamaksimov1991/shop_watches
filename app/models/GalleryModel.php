<?php
namespace app\models;

/**
 * Class ProductModel
 *
 * @property int $id
 * @property int $product_id
 * @property string $img
 *
 * @package app\models
 */
class GalleryModel extends AppModel
{

    /**
     * Get gallery products
     *
     * @param int $id
     * @return array
     */
    public function getGalleryProducts($id)
    {
        return \R::findAll('gallery', 'product_id = ?', [$id]);
    }

    /**
     * @param string $column
     * @param array $product
     * @return array
     */
    public function getColumn($column, array $product)
    {
        return \R::getCol("SELECT {$column} FROM gallery WHERE {$this->prepareQueryColumns($product)}", $this->getQueryValues($product));
    }

    /**
     * @param array $array
     * @return int
     */
    public function delete($array)
    {
        return \R::exec("DELETE FROM gallery WHERE {$this->prepareQueryColumns($array)}", $this->getQueryValues($array));
    }

    /**
     * @param string $sql
     * @return int
     */
    public function addImagesGallery($sql)
    {
        return \R::exec("INSERT INTO gallery (img, product_id) VALUES $sql");
    }
}