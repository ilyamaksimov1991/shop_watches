<?php
namespace app\models;

/**
 * Class Product
 * @package app\models
 */
class Product extends AppModel
{

    /**
     *  Get three recently viewed products
     *
     * @return array|null
     */
    public function getThreeRecentlyViewedProducts()
    {
        $r_viewed = $this->getThreeLastRecentlyViewedProductFromTheCookie();
        $recentlyViewed = null;
        if ($r_viewed) {
            $recentlyViewed = (new ProductModel())->getProducts($r_viewed);
        }
        return $recentlyViewed;
    }


    /**
     * Write the product in the cookies
     *
     * @param int $id
     */
    public function writeTheProductInTheCookies($id)
    {
        $recentlyViewed = $this->getAllRecentlyViewedProductsFromCookies();
        if (!$recentlyViewed) {
            setcookie('recentlyViewed', $id, time() + 3600 * 24, '/');
        } else {
            $recentlyViewed = explode('.', $recentlyViewed);
            $threeLastRecentlyViewedProduct = array_slice($recentlyViewed, -3);
            if (!in_array($id, $threeLastRecentlyViewedProduct)) {
                $recentlyViewed[] = $id;
                $recentlyViewed = implode('.', $recentlyViewed);
                setcookie('recentlyViewed', $recentlyViewed, time() + 3600 * 24, '/');
            }
        }
    }

    /**
     * Get three last recently viewed product from the cookie
     *
     * @return array|bool
     */
    private function getThreeLastRecentlyViewedProductFromTheCookie()
    {
        if (!empty($_COOKIE['recentlyViewed'])) {
            $recentlyViewed = $_COOKIE['recentlyViewed'];
            $recentlyViewed = explode('.', $recentlyViewed);
            return array_slice($recentlyViewed, -3);
        }
        return false;
    }

    /**
     * Get all recently viewed products from cookies
     *
     * @return bool
     */
    private function getAllRecentlyViewedProductsFromCookies()
    {
        if (!empty($_COOKIE['recentlyViewed'])) {
            return $_COOKIE['recentlyViewed'];
        }
        return false;
    }
}