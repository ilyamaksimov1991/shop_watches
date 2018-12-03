<?php
namespace app\controllers\admin;

use app\models\OrderModel;
use app\models\User;
use app\models\UserModel;
use framework\App;
use framework\libs\Pagination;

/**
 * Class UserController
 * @package app\controllers\admin
 */
class UserController extends AppController {

    public function loginAdminAction(){
        if(!empty($_POST)){
            $user = new User();
            if($user->isLogin(true)){
                $_SESSION['success'] = 'Вы успешно авторизованы';
            }else{
                $_SESSION['error'] = 'Логин/пароль введены неверно';
            }

            if(User::isAdmin()){
                redirect(ADMIN_URL);
            }else{
                redirect();
            }
        }
        $this->layout = 'login';
    }


    public function indexAction(){
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        $countUsersPerPage = App::$app->getProperty('pagination_admin_users');
        $count = (new UserModel())->getCountUsers();
        $pagination = new Pagination($page, $countUsersPerPage, $count);

        $start = $pagination->getStart();
        $users = (new UserModel)->getUsersForPagination($start, $countUsersPerPage);

        $this->setMeta('Список пользователей');
        $this->render(compact('users', 'pagination', 'count'));
    }

    public function addAction(){
        $this->setMeta('Новый пользователь');
    }

    public function editAction(){
        if(!empty($_POST)){

            $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
            if(!$id){
                throw new \Exception('Страница не найдена', 404);
            }

            $user = new \app\models\admin\User();

            $user->load($_POST);
            if(!$user->attributes['password']){
                unset($user->attributes['password']);
            }else{
                $user->attributes['password'] = password_hash($user->attributes['password'], PASSWORD_DEFAULT);
            }
            if(!$user->validate($_POST) || !$user->checkUnique()){
                $user->getErrors();
                redirect();
            }
            if($user->update('user', $id)){
                $_SESSION['success'] = 'Изменения сохранены';
            }
            redirect();
        }


        $userId = !empty($_GET['id']) ? (int)$_GET['id'] : null;
        if(!$userId){
            throw new \Exception('Страница не найдена', 404);
        }

        $user = (new UserModel())->getUser($userId);

        $orders = (new OrderModel())->getFullInformationAboutTheUserOrder($userId);

        $this->setMeta('Редактирование профиля пользователя');
        $this->render(compact('user', 'orders'));
    }

}