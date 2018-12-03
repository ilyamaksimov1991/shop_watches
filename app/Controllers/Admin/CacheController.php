<?php

namespace app\Controllers\Admin;

use framework\Cache;

/**
 * Class CacheController
 * @package app\controllers\admin
 */
class CacheController extends AppController
{
    public function indexAction()
    {
        $this->setMeta('Очистка кэша');
    }

    public function deleteAction()
    {
        $key = $_GET['key'] ?? null; //Better to create request object

        (new Cache())->deleteCache($key);

        $_SESSION['success'] = 'Выбранный кэш удален';
        redirect();
    }
}
