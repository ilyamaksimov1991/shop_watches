<?php
namespace app\Models;

/**
 * BrandModel
 *
 * @package app\models
 */
class BrandModel extends AppModel
{

    /**
     * Get brand
     *
     * Displays 3 brands by default
     *
     * @param int $limit
     * @return array
     */
    public function getBrand($limit = 3)
    {
        return \R::find('brand', "LIMIT {$limit}");
    }
}
