<?php

$breadcrumbs = "<li><a href='" . PATH . "'>Главная</a></li>";
if($breadcrumbs_array){
    foreach($breadcrumbs_array as $alias => $title){
        $breadcrumbs .= "<li><a href='" . PATH . "/category/{$alias}'>{$title}</a></li>";
    }
}
if($name){
    $breadcrumbs .= "<li>$name</li>";
}