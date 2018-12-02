<?php

namespace app\widgets\breadcrumbs;

use framework\App;

/**
 * Class Breadcrumbs
 * @package app\widgets\menu
 */
class Breadcrumbs
{
    private $categoryId;
    private $name;

    public function __construct($options = [])
    {
        $this->getOptions($options);
        $this->run();
    }

    private function getOptions($options)
    {
        foreach ($options as $k => $v) {
            if (property_exists($this, $k)) {
                $this->$k = $v;
            }
        }
    }
    private function run($options)
    {
        return $this->getBreadcrumbs();
    }



    /**
     * @param string $category_id
     * @param string $name
     * @return string
     */
    public  function getBreadcrumbs($category_id, $name = '')
    {

        $cats = App::$app->getProperty('cats');
        $breadcrumbs_array = self::getParts($cats, $category_id);

        return $this->getHtml($breadcrumbs_array, $name);

    }

    public static function getParts($cats, $id)
    {
        if (!$id) return false;
        $breadcrumbs = [];
        foreach ($cats as $k => $v) {
            if (isset($cats[$id])) {
                $breadcrumbs[$cats[$id]['alias']] = $cats[$id]['title'];
                $id = $cats[$id]['parent_id'];
            } else break;
        }
        return array_reverse($breadcrumbs, true);
    }

    private function getHtml($breadcrumbs_array, $name)
    {
        $breadcrumbs = "<li><a href='" . PATH . "'>Главная</a></li>";
        if ($breadcrumbs_array) {
            foreach ($breadcrumbs_array as $alias => $title) {
                $breadcrumbs .= "<li><a href='" . PATH . "/category/{$alias}'>{$title}</a></li>";
            }
        }
        if ($name) {
            $breadcrumbs .= "<li>$name</li>";
        }

        return $breadcrumbs;
    }

}