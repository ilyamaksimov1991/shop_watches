<?php
namespace app\models;

use framework\App;

/**
 * Class category
 * @package app\models
 */
class Category extends AppModel
{

    public $attributes = [
        'title' => '',
        'parent_id' => '',
        'keywords' => '',
        'description' => '',
        'alias' => '',
    ];

    public $rules = [
        'required' => [
            ['title'],
        ]
    ];

    /**
     * @param $id
     * @return null|string
     */
    public function getIdsOfTheCategories($id)
    {
        $cats = App::$app->getProperty('cats');
        $ids = null;
        foreach ($cats as $k => $v) {
            if ($v['parent_id'] == $id) {
                $ids .= $k . ',';
                $ids .= $this->getIdsOfTheCategories($k);
            }
        }
        return $ids;
    }

    /**
     * Get all child categories
     *
     * @param $id
     * @return null|string
     */
    public function convertIdArrayToString($id)
    {
        $cats = App::$app->getProperty('cats');
        $ids = null;
        foreach ($cats as $k => $v) {
            if ($v['parent_id'] == $id) {
                $ids .= $k . ',';
                $ids .= $this->convertIdArrayToString($k);
            }
        }
        return $ids;
    }

    /**
     * @param $id
     * @return string
     */
    public function getStringIdsOfTheChildCategoriesSelected($id)
    {
        $idString = $this->convertIdArrayToString($id);
        return !$idString ? $id : $idString . $id;
    }

    /**
     * @param int $id
     * @return string
     */
    public function checkProductsInCategoryAndCheckOfChildCategoriesAndErrorDisplays($id)
    {
        $category = new CategoryModel();

        $children = $category->checkProductsInCategory($id);
        $errors = '';
        if ($children) {
            $errors .= '<li>Удаление невозможно, в категории есть вложенные категории</li>';
        }

        $products = $category->checkOfChildCategories($id);
        if ($products) {
            $errors .= '<li>Удаление невозможно, в категории есть товары</li>';
        }

        return $errors;
    }

}