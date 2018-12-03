<?php
namespace app\models;

use framework\App;

/**
 * Class Breadcrumbs
 * @package app\models
 */
class Breadcrumbs
{

    /**
     * @param $category_id
     * @param string $name
     * @return string
     */
    public static function getBreadcrumbs($category_id, $name = ''){

        $cats = App::$app->getProperty('cats');
        $breadcrumbs_array = self::getParts($cats, $category_id);
        $breadcrumbs = "<li><a href='" . SITE_URL . "'>Главная</a></li>";
        if($breadcrumbs_array){
            foreach($breadcrumbs_array as $alias => $title){
                $breadcrumbs .= "<li><a href='" . SITE_URL . "/category/{$alias}'>{$title}</a></li>";
            }
        }
        if($name){
            $breadcrumbs .= "<li>$name</li>";
        }
        return $breadcrumbs;
    }

    public static function getParts($cats, $id){
        if(!$id) return false;
        $breadcrumbs = [];
        foreach($cats as $k => $v){
            if(isset($cats[$id])){
                $breadcrumbs[$cats[$id]['alias']] = $cats[$id]['title'];
                $id = $cats[$id]['parent_id'];
            }else break;
        }
        return array_reverse($breadcrumbs, true);
    }


}