<?php
namespace app\models;

/**
 * AttributeGroupModel
 *
 * @property int $id
 * @property string $title
 *
 * @package app\models
 */
class AttributeGroupModel extends AppModel
{

    /**
     * @return array
     */
    public function getGroups()
    {
        //return \R::getAssoc('SELECT id, title FROM attribute_group');
        return \R::getAssoc('SELECT * FROM attribute_group');
    }

    /**
     * @param int $id
     * @return int
     */
    public function delete($id)
    {
        return \R::exec('DELETE FROM attribute_group WHERE id = ?', [$id]);
    }

    /**
     * @param int $id
     * @return \RedBeanPHP\OODBBean
     */
    public function getGroup($id)
    {
        return \R::load('attribute_group', $id);
    }
}