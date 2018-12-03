<?php

namespace app\Controllers\Admin;

use app\Models\AppModel;
use app\Models\User;
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
            redirect(ADMIN_URL . '/user/login-admin');
        }
        new AppModel();

    }

}