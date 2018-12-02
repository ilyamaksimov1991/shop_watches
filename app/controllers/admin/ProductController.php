<?php
namespace app\controllers\admin;

use app\models\admin\Product;
use app\models\AppModel;
use app\models\AttributeProductModel;
use app\models\GalleryModel;
use app\models\ProductModel;
use app\models\RelatedProductModel;
use framework\App;
use framework\libs\Pagination;

/**
 * Class ProductController
 * @package app\controllers\admin
 */
class ProductController extends AppController
{

    public function indexAction()
    {
        $productsModel = new ProductModel();
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $countProductPerPage = App::$app->getProperty('pagination_admin_products');
        $count = $productsModel->getCountProducts();
        $pagination = new Pagination($page, $countProductPerPage, $count);
        $start = $pagination->getStart();

        $products = $productsModel->getProductsAndCategoryTitle($start, $countProductPerPage);

        $this->setMeta('Список товаров');
        $this->render(compact('products', 'pagination', 'count'));
    }

    public function addImageAction()
    {
        if (isset($_GET['upload'])) {
            $name = $_POST['name'];

            if ($name == 'single') {
                $widthMax = App::$app->getProperty('img_width');
                $heightMax = App::$app->getProperty('img_height');
            } else {
                $widthMax = App::$app->getProperty('gallery_width');
                $heightMax = App::$app->getProperty('gallery_height');
            }

            $product = new Product();
            $product->uploadImg($name, $widthMax, $heightMax);
        }
    }

    public function editAction()
    {
        if (!empty($_POST)) {
            $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
            if (!$id) {
                throw new \Exception('Страница не найдена', 404);
            }
            $product = new Product();

            $product->load($_POST);
            $product->attributes['status'] = $product->attributes['status'] ? '1' : '0';
            $product->attributes['hit'] = $product->attributes['hit'] ? '1' : '0';

            $product->getImg();
            if (!$product->validate($_POST)) {
                $product->getErrors();
                redirect();
            }
            if ($product->update('product', $id)) {
                $product->editFilter($id, $_POST);
                $product->editRelatedProduct($id, $_POST);
                $product->saveGallery($id);
                $alias = AppModel::createAlias('product', 'alias', $_POST['title'], $id);

                (new ProductModel())->saveAliasOfTheProducts($alias, $id);

                $_SESSION['success'] = 'Изменения сохранены';
                redirect();
            }
        }

        $id = !empty($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) {
            throw new \Exception('Страница не найдена', 404);
        }


        $product = (new ProductModel())->getOneProduct(['id' => $id]);
        App::$app->setProperty('parent_id', $product->category_id);

        $filter = (new AttributeProductModel())->getColumn('attr_id', ['product_id' => $id]);
        $relatedProduct = (new RelatedProductModel())->getRelatedProducts($id);
        $gallery = (new GalleryModel())->getColumn('img', ['product_id' => $id]);

        $this->setMeta("Редактирование товара {$product->title}");
        $this->render(compact('product', 'filter', 'relatedProduct', 'gallery'));
    }

    public function addAction()
    {
        if (!empty($_POST)) {
            $product = new Product();

            $product->load($_POST);
            $product->attributes['status'] = $product->attributes['status'] ? '1' : '0';
            $product->attributes['hit'] = $product->attributes['hit'] ? '1' : '0';
            $product->getImg();

            if (!$product->validate($_POST)) {
                $product->getErrors();
                $_SESSION['form_data'] = $_POST;
                redirect();
            }

            if ($id = $product->save('product')) {
                $product->saveGallery($id);
                $alias = AppModel::createAlias('product', 'alias', $_POST['title'], $id);

                (new ProductModel())->saveAliasOfTheProducts($alias, $id);

                $product->editFilter($id, $_POST);
                $product->editRelatedProduct($id, $_POST);
                $_SESSION['success'] = 'Товар добавлен';
            }
            redirect();
        }

        $this->setMeta('Новый товар');
    }

    public function relatedProductAction()
    {
        $search = isset($_GET['q']) ? $_GET['q'] : '';
        $data['items'] = [];

        $products = (new ProductModel())->getProductsFromTheSearchQuery($search);
        if ($products) {
            $i = 0;
            foreach ($products as $id => $title) {
                $data['items'][$i]['id'] = $id;
                $data['items'][$i]['text'] = $title;
                $i++;
            }
        }
        echo json_encode($data);
        die;
    }

    public function deleteGalleryAction()
    {
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $src = isset($_POST['src']) ? $_POST['src'] : null;
        if (!$id || !$src) {
            return;
        }

        if ((new GalleryModel())->delete(['product_id' => $id, 'img' => $src])) {
            @unlink(WWW . "/images/$src");
            exit('1');
        }
        return;
    }

}