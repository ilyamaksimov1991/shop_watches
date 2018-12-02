<?php
namespace app\models;

/**
 * Class ProductModel
 *
 * @property int $id
 * @property int $category_id
 * @property int $brand_id
 * @property string $title
 * @property string $alias
 * @property string $content
 * @property int $price
 * @property int $old_price
 * @property boolean $status
 * @property string $keywords
 * @property string $description
 * @property string $img
 * @property string $hit
 *
 * @package app\models
 */
class ProductModel extends AppModel
{

    /**
     * Get one product
     * @param $array
     * @return NULL|\RedBeanPHP\OODBBean
     */
    public function getOneProduct($array)
    {
        return \R::findOne('product', $this->prepareQueryColumns($array), $this->getQueryValues($array));
    }

    /**
     * Get product hit
     *
     * Displays 8 product hit by default
     *
     * @param int $limit
     * @return array
     */
    public function getProductHit($limit = 8)
    {
        return \R::find('product', "hit = '1' AND status = '1' LIMIT {$limit}");
    }

    /**
     * Get products
     *
     * @param array $arrayId
     * @param string $column
     * @return array
     */
    public function getProducts($arrayId, $column = 'id')
    {
        return \R::find('product', "{$column} IN (" . \R::genSlots($arrayId) . ") LIMIT 3", $arrayId);
    }

    /**
     * Find like products
     *
     * @param $search
     * @param string $column
     * @return array
     */
    public function getProductLike($search, $column = 'title')
    {
        return \R::find('product', "{$column} LIKE ?", ["%{$search}%"]);
    }

    /**
     *  Get productC categories
     *
     * @param string $stringWithId
     * @return array
     */
    public function getProductCategories($stringWithId)
    {
        return \R::find('product', "category_id IN ($stringWithId)");
    }

    /**
     * Find the id and title of products to search
     *
     * @param $search
     * @return array
     */
    public function getProductsFromTheSearchQuery($search)
    {
        return \R::getAll('SELECT id, title 
                                FROM product 
                                WHERE title LIKE ? LIMIT 6', ["%{$search}%"]);
    }

    /**
     * Get products for pagination
     *
     * @param string $idStringChildCategories
     * @param string $sqlFilter
     * @param int $start
     * @param int $countProductPerPage
     * @return array
     */
    public function getProductsForPagination($idStringChildCategories, $sqlFilter, $start, $countProductPerPage)
    {
        return \R::find('product', "category_id IN ($idStringChildCategories) $sqlFilter LIMIT $start, $countProductPerPage");
    }

    /**
     * Get count of products in categories
     *
     * @param $idString
     * @return int
     */
    public function getCountOfProductsInCategories($idString, $sqlFilter)
    {
        return \R::count('product', "category_id IN ($idString) $sqlFilter");;
    }

    /**
     * @return int
     */
    public function getCountProducts()
    {
        return \R::count('product');
    }

    public function getProductsAndCategoryTitle($start, $countProductPerpage)
    {
        return \R::getAll("SELECT product.*, category.title AS cat 
                            FROM product 
                            JOIN category ON category.id = product.category_id 
                            ORDER BY product.title 
                            LIMIT $start, $countProductPerpage");

    }


    /**
     * @param string $alias
     * @param $id
     */
    public function saveAliasOfTheProducts($alias, $id)
    {
        /**
         * @var self $product
         */
        $product = \R::load('product', $id);
        $product->alias = $alias;
        \R::store($product);
    }


}