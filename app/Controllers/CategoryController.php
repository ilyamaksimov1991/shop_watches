<?php
namespace app\Controllers;

use app\Models\Breadcrumbs;
use app\Models\Category;
use app\Models\CategoryModel;
use app\Models\ProductModel;
use app\widgets\filter\Filter;
use framework\App;
use framework\libs\Pagination;

/**
 * Class CategoryController
 * @package app\controllers
 */
class CategoryController extends AppController
{

    public function viewAction()
    {
        $alias = $this->route['alias'];
        $category = (new CategoryModel())->getOneCategory(['alias' => $alias]);
        if (!$category) {
            throw new \Exception('Страница не найдена', 404);
        }

        $breadcrumbs = Breadcrumbs::getBreadcrumbs($category->id);

        $idStringChildCategories = (new Category())->getStringIdsOfTheChildCategoriesSelected($category->id);

        $sqlFilter = Filter::getSqlQueryIdProductsThatSatisfyFiltert();


        $total = (new ProductModel())->getCountOfProductsInCategories($idStringChildCategories, $sqlFilter);
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $countProductPerPage = App::$app->getProperty('pagination');
        $pagination = new Pagination($page, $countProductPerPage, $total);
        $start = $pagination->getStart();


        $products = (new ProductModel())->getProductsForPagination($idStringChildCategories, $sqlFilter, $start, $countProductPerPage);

        if ($this->isAjax()) {
            $this->loadView('filter', compact('products', 'total', 'pagination'));
        }

        $this->setMeta($category->title, $category->description, $category->keywords);
        $this->render(compact('products', 'breadcrumbs', 'pagination', 'total'));
    }

}