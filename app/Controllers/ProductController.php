<?php
namespace app\Controllers;

use app\Models\Breadcrumbs;
use app\Models\GalleryModel;
use app\Models\ModificationProductModel;
use app\Models\Product;
use app\Models\ProductModel;
use app\Models\RelatedProductModel;

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