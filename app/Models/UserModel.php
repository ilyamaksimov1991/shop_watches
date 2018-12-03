<?php
namespace app\Models;

/**
 * UserModel
 *
 * @property int $id
 * @property string $login
 * @property string $password
 * @property string $email
 * @property string $name
 * @property string $address
 * @property string $role
 *
 * @package app\models
 */
class UserModel extends AppModel
{

    /**
     * Check unique login and email
     *
     * @param string $login
     * @param string $email
     * @return NULL|\RedBeanPHP\OODBBean
     */
    public function checkUniqueLoginAndEmail($login, $email)
    {
        return \R::findOne('user', 'login = ? OR email = ?', [$login, $email]);
    }

    /**
     * @param string $login
     * @param string $email
     * @param int $id
     * @return NULL|\RedBeanPHP\OODBBean
     */
    public function checkUniqueLoginAndEmailAndNotId($login, $email,$id)
    {
       return \R::findOne('user', '(login = ? OR email = ?) AND id <> ?', [$login, $email, $id]);

    }

    /**
     * @param string $login
     * @return NULL|\RedBeanPHP\OODBBean
     */
    public function getUserByLogin($login)
    {
        return \R::findOne('user', "login = ?", [$login]);
    }

    /**
     * @param string $login
     * @return NULL|\RedBeanPHP\OODBBean
     */
    public function getAdmin($login)
    {
        return \R::findOne('user', "login = ? AND role = 'admin'", [$login]);
    }

    /**
     * @return int
     */
    public function getCountUsers()
    {
        return \R::count('user');
    }


    /**
     * @param int $start
     * @param int $countUsersPerPage
     * @return array
     */
    public function getUsersForPagination($start, $countUsersPerPage)
    {
        return \R::findAll('user', "LIMIT $start, $countUsersPerPage");
    }

    /**
     * @param int $userId
     * @return \RedBeanPHP\OODBBean
     */
    public function getUser($userId)
    {
    return \R::load('user', $userId);
    }
}