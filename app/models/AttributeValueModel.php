<?php
namespace app\models;

/**
 * AttributeValueModel
 *
 * @property int $id
 * @property string $value
 * @property int $attr_group_id
 *
 * @package app\models
 */
class AttributeValueModel extends AppModel
{

    /**
     * @return array
     */
    public function getAttributesValue()
    {
        return \R::getAssoc('SELECT * FROM attribute_value');
    }

    /**
     * @param int $id
     * @return \RedBeanPHP\OODBBean
     */
    public function getAttributeValue($id)
    {
        return \R::load('attribute_value', $id);
    }

    /**
     * @param int $groupId
     * @return int
     */
    public function getCountAttributeValueByGroup($groupId)
    {
        return \R::count('attribute_value', 'attr_group_id = ?', [$groupId]);
    }

    /**
     * @param int $id
     * @return int
     */
    public function delete($id)
    {
        return \R::exec("DELETE FROM attribute_value WHERE id = ?", [$id]);
    }

    /**
     * @return array
     */
    public function getAttributesValuesAndTitleGroup()
    {
        return \R::getAssoc("SELECT attribute_value.*, attribute_group.title 
                                  FROM attribute_value 
                                  JOIN attribute_group ON attribute_group.id = attribute_value.attr_group_id");
    }

}