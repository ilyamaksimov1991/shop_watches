<?php

$breadcrumbs = "<li><a href='" . SITE_URL . "'>Главная</a></li>";
if($breadcrumbs_array){
    foreach($breadcrumbs_array as $alias => $title){
        $breadcrumbs .= "<li><a href='" . SITE_URL . "/category/{$alias}'>{$title}</a></li>";
    }
}
if($name){
    $breadcrumbs .= "<li>$name</li>";
}