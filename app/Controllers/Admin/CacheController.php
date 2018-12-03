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
        $key = isset($_GET['key']) ? $_GET['key'] : null;

        (new Cache())->deleteCache($key);

        $_SESSION['success'] = 'Выбранный кэш удален';
        redirect();
    }

}