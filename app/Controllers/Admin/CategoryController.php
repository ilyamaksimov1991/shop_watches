<?php

namespace app\Controllers\Admin;

use app\Models\AppModel;
use app\Models\Category;
use app\Models\CategoryModel;
use framework\App;

/**
 * Class CategoryController
 * @package app\controllers\admin
 */
class CategoryController extends AppController
{

    public function indexAction()
    {
        $this->setMeta('Список категорий');
    }

    public function deleteAction()
    {
        $id = !empty($_GET['id']) ? (int)$_GET['id'] : null;

        if (!$id) {
            throw new \Exception('Категории не существует', 404);
        }

        try {
            (new Category())->checkCategoriesBeforeDelete($id);//creating object and call func to many for if //to long method name
        } catch (SomeBaseException $e) {
            $_SESSION['error'] = $e->getMessage();
            redirect();
        }

        (new CategoryModel())->deleteCategory($id);

        $_SESSION['success'] = 'Категория удалена';
        redirect();
    }

    public function addAction()
    {
        $category = new Category();
        $category->load($_POST);
        if (!$category->validate($_POST)) {
            $category->getErrors();// if empty post will be error here
            redirect();
        }

        if ($id = $category->save('category')) {
            $alias = AppModel::createAlias('category', 'alias', $_POST['title'], $id);
            (new CategoryModel())->saveAliasOfTheCategory($alias, $id);

            $_SESSION['success'] = 'Категория добавлена';
        }

        redirect();
        $this->setMeta('Новая категория');
    }

    public function editAction()
    {

        //get and post not in one action
        if (!empty($_POST)) {
            $id = $_POST['id'];
            if (!$id) {
                throw new \Exception('Категории не существует', 404);
            }

            $category = new Category();
            $category->load($_POST);
            if (!$category->validate($_POST)) {
                $category->getErrors();
                redirect();
            }

            if ($category->update('category', $id)) {

                $alias = AppModel::createAlias('category', 'alias', $_POST['title'], $id);
                (new CategoryModel())->saveAliasOfTheCategory($alias, $id);

                $_SESSION['success'] = 'Изменения сохранены';
            }
            redirect();
        }

        $id = $_GET['id'];
        if (!$id) {
            throw new \Exception('Категории не существует', 404);
        }

        $category = (new CategoryModel())->getCategory($id);
        App::$app->setProperty('parent_id', $category->parent_id);

        $this->setMeta("Редактирование категории {$category->title}");
        $this->render(compact('category'));
    }
}
