<?php
namespace app\Models;

/**
 * Class user
 * @package app\models
 */
class User extends AppModel
{
    public $attributes = [
        'login' => '',
        'password' => '',
        'name' => '',
        'email' => '',
        'address' => '',
    ];

    /**
     * @var array $rules validation rules
     */
    public $rules = [
        'required' => [
            ['login'],
            ['password'],
            ['name'],
            ['email'],
            ['address'],
        ],
        'email' => [
            ['email'],
        ],
        'lengthMin' => [
            ['password', 6],
        ]
    ];

    /**
     * Output error text if the login or email is not unique
     *
     * @return bool
     */
    public function checkUniqueLoginAndEmail()
    {
        /**
         * @var UserModel $user
         */
        $user = (new UserModel())->checkUniqueLoginAndEmail($this->attributes['login'], $this->attributes['email']);

        if ($user) {
            if ($user->login == $this->attributes['login']) {
                $this->errors['unique'][] = 'Этот логин уже занят';
            }
            if ($user->email == $this->attributes['email']) {
                $this->errors['unique'][] = 'Этот email уже занят';
            }
            return false;
        }
        return true;
    }

    /**
     * Verification of the user's login and password to enter the personal account
     *
     * @param bool $isAdmin
     * @return bool
     */
    public function isLogin($isAdmin = false)
    {
        $login = !empty(trim($_POST['login'])) ? trim($_POST['login']) : null;
        $password = !empty(trim($_POST['password'])) ? trim($_POST['password']) : null;

        $userModel = new UserModel();

        if ($login && $password) {
            if ($isAdmin) {
                $user = $userModel->getAdmin($login);
            } else {
                $user = $userModel->getUserByLogin($login);
            }
            /**
             * @var UserModel $user
             */
            if ($user) {
                if (password_verify($password, $user->password)) {
                    foreach ($user as $k => $v) {
                        if ($k != 'password') $_SESSION['user'][$k] = $v;
                    }
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Registration of users
     *
     * @param $data
     */
    public function userSignUp($data)
    {
        $this->load($data);
        if (!$this->validate($data) || !$this->checkUniqueLoginAndEmail()) {
            $this->getErrors();
            $_SESSION['form_data'] = $data;
        } else {
            $this->attributes['password'] = password_hash($this->attributes['password'], PASSWORD_DEFAULT);
            if ($this->save('user')) {
                $_SESSION['success'] = 'Пользователь зарегистрирован';
            } else {
                $_SESSION['error'] = 'Ошибка!';
            }
        }
    }



    /**
     * @return bool
     */
    public static function  isUserAuthorized(){
        return isset($_SESSION['user']);
    }

    /**
     * @return bool
     */
    public static function isAdmin(){
        return (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin');
    }

}