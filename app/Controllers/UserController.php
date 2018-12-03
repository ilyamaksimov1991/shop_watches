<?php
namespace app\Controllers;

use app\Models\User;

/**
 * Class UserController
 *
 * @package app\controllers
 */
class UserController extends AppController
{

    public function signupAction()
    {
        if (!empty($_POST)) {
            $data = $_POST;

            $user = new User();
            $user->userSignUp($data);

            redirect();
        }
        $this->setMeta('Регистрация');
    }

    public function loginAction()
    {
        if (!empty($_POST)) {
            $user = new User();
            if ($user->isLogin()) {
                $_SESSION['success'] = 'Вы успешно авторизованы';
            } else {
                $_SESSION['error'] = 'Логин/пароль введены неверно';
            }
            redirect();
        }
        $this->setMeta('Вход');
    }

    public function logoutAction()
    {
        if (isset($_SESSION['user'])) unset($_SESSION['user']);
        redirect();
    }

}