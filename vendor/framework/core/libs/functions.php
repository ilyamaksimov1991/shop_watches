<?php

/**
 * Displays an array in readable form
 *
 * @param $array
 */
function print_arr($array)
{
    echo '<pre>' . print_r($array, true) . '</pre>';
}


/**
 * Redirects to the page from which you came or to the specified url
 *
 * @param bool|string $http
 */
function redirect($http = false)
{
    if ($http) {
        $redirect = $http;
    } else {
        $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : PATH;
    }
    header("Location: $redirect");
    exit;
}

/**
 * The wrapper on htmlspecialchars
 *
 * @param $str
 * @return string
 */
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES);
}

 function prepareQueryColumns($array)
{
    $arg = func_get_args();
    $keys = array_keys($arg[0]);
    //$values = array_values($arg[0]);
    $sql = [];
    foreach ($keys as $val) {
        if(is_array($arg[0][$val])){
            $sql[]= "$val IN ( ?, ?) ";

        }else{
            $sql[] = "$val = ?";
        }

    }

    return (implode(' AND ', $sql));

}