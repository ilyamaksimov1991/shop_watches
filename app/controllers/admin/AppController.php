<?php
namespace app\controllers\admin;

use app\models\AppModel;
use app\models\User;
use framework\base\AbstractController;

/**
 * Class AppController
 * @package app\controllers\admin
 */
class AppController extends AbstractController
{
    public $layout = 'admin';

    public function __construct($route)
    {
        parent::__construct($route);
        if (!User::isAdmin() && $route['action'] != 'login-admin') {
            redirect(ADMIN . '/user/login-admin');
        }
        new AppModel();

    }

}