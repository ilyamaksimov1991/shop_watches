<?php

namespace app\Controllers;

use app\Models\AppModel;
use app\Models\CurrencyModel;
use framework\base\AbstractController;
use framework\App;
use app\widgets\currency\Currency;
use framework\Cache;

/**
 * Class AppController
 * @package app\controllers
 */
class AppController extends AbstractController
{
    public function __construct($route)
    {
        parent::__construct($route);
        App::$app->setProperty('currencies', (new CurrencyModel())->getAllCurrencies());
        App::$app->setProperty('currency', Currency::getCurrency(App::$app->getProperty('currencies')));
        App::$app->setProperty('cats', Cache::cacheCategory());
    }
}