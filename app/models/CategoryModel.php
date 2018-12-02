<?php
namespace app\models;

/**
 * CategoryModel
 *
 * @property int $id
 * @property string $title
 * @property string $alias
 * @property int $parent_id
 * @property string $keywords
 * @property string $description
 *
 * @package app\models
 */
class CategoryModel extends AppModel
{

    /**
     * Get information about the categories
     *
     * @return array
     */
    public function getCategories()
    {
        return \R::getAssoc("SELECT * FROM category");
    }

    /**
     * Get information about the category
     *
     * @param int $id
     * @return \RedBeanPHP\OODBBean
     */
    public function getCategory($id)
    {
        return \R::load('category', $id);
    }

    /**
     *  Get one category
     *
     * @param $array
     * @return NULL|\RedBeanPHP\OODBBean
     */
    public function getOneCategory($array)
    {
        return \R::findOne('category', $this->prepareQueryColumns($array), $this->getQueryValues($array));
    }

    /**
     * @return int
     */
    public function getCountCategories()
    {
        return \R::count('category');
    }

    /**
     * @param int $id
     */
    public function deleteCategory($id)
    {
        $category = $this->getCategory($id);
        \R::trash($category);
    }

    /**
     * @param int $id
     * @return int
     */
    public function checkProductsInCategory($id)
    {
        return \R::count('category', 'parent_id = ?', [$id]);
    }

    /**
     * @param int $id
     * @return int
     */
    public function checkOfChildCategories($id)
    {
        return \R::count('product', 'category_id = ?', [$id]);
    }

    /**
     * @param string $alias
     * @param $id
     */
    public function saveAliasOfTheCategory($alias, $id)
    {
        /**
         * @var self $category
         */
        $category = \R::load('category', $id);
        $category->alias = $alias;
        \R::store($category);
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

}