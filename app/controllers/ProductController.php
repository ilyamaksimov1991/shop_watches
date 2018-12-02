<?php
namespace app\controllers;

use app\models\Breadcrumbs;
use app\models\GalleryModel;
use app\models\ModificationProductModel;
use app\models\Product;
use app\models\ProductModel;
use app\models\RelatedProductModel;

/**
 * Class ProductController
 * @package app\controllers
 */
class ProductController extends AppController
{

    public function viewAction()
    {
        $alias = $this->route['alias'];

        $product = (new ProductModel())->getOneProduct(['alias' => $alias, 'status' => '1']);
        if (!$product) {
            throw new \Exception("Подукт {$alias} не найден ", 404);
        }

        // хлебные крошки
        $breadcrumbs = Breadcrumbs::getBreadcrumbs($product->category_id, $product->title);

        // связанные товары
        $related = (new RelatedProductModel())->getRelatedProducts($product->id);

        $products = new Product();
        // запись в куки запрошенного товара
        $products->writeTheProductInTheCookies($product->id);

        // недавно просмотренные продукты
        $recentlyViewed = $products->getThreeRecentlyViewedProducts();


        // галерея
        $gallery = (new GalleryModel())->getGalleryProducts($product->id);

        // модификации
        $mods = (new ModificationProductModel())->getProducts(['product_id' => $product->id]);

        $this->setMeta($product->title, $product->description, $product->keywords);
        $this->render(compact('product', 'related', 'gallery', 'recentlyViewed', 'breadcrumbs', 'mods'));
    }
}