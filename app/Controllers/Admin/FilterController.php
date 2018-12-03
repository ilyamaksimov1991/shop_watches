<?php
namespace app\Controllers\Admin;

use app\Models\admin\FilterAttr;
use app\Models\admin\FilterGroup;
use app\Models\AttributeGroupModel;
use app\Models\AttributeProductModel;
use app\Models\AttributeValueModel;

/**
 * Class FilterController
 * @package app\controllers\admin
 */
class FilterController extends AppController
{

    public function groupDeleteAction()
    {
        $id = !empty($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) {
            throw new \Exception('Страница не найдена', 404);
        }

        $count = (new AttributeValueModel())->getCountAttributeValueByGroup($id);
        if ($count) {
            $_SESSION['error'] = 'Удаление невозможно, в группе есть атрибуты';
            redirect();
        }
        (new AttributeGroupModel())->delete($id);
        $_SESSION['success'] = 'Удалено';
        redirect();
    }

    public function attributeDeleteAction()
    {
        $id = !empty($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) {
            throw new \Exception('Страница не найдена', 404);
        }

        (new AttributeProductModel())->delete(['attr_id' => $id]);
        (new AttributeValueModel())->delete($id);

        $_SESSION['success'] = 'Удалено';
        redirect();
    }

    public function attributeEditAction()
    {
        if (!empty($_POST)) {
            $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
            if (!$id) {
                throw new \Exception('Страница не найдена', 404);
            }

            $attributes = new FilterAttr();
            $attributes->load($_POST);
            if (!$attributes->validate($_POST)) {
                $attributes->getErrors();
                redirect();
            }
            if ($attributes->update('attribute_value', $id)) {
                $_SESSION['success'] = 'Изменения сохранены';
                redirect();
            }
        }
        $id = !empty($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) {
            throw new \Exception('Страница не найдена', 404);
        }

        $attributes = (new AttributeValueModel())->getAttributeValue($id);
        $attributesGroup = (new AttributeGroupModel())->getGroups();

        $this->setMeta('Редактирование атрибута');
        $this->render(compact('attributes', 'attributesGroup'));
    }

    public function attributeAddAction()
    {
        if (!empty($_POST)) {
            $attr = new FilterAttr();

            $attr->load($_POST);
            if (!$attr->validate($_POST)) {
                $attr->getErrors();
                redirect();
            }
            if ($attr->save('attribute_value', false)) {
                $_SESSION['success'] = 'Атрибут добавлен';
                redirect();
            }
        }
        $group = (new AttributeGroupModel())->getGroups();

        $this->setMeta('Новый фильтр');
        $this->render(compact('group'));
    }

    public function groupEditAction()
    {
        if (!empty($_POST)) {
            $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
            if (!$id) {
                throw new \Exception('Страница не найдена', 404);
            }

            $group = new FilterGroup();
            $group->load($_POST);
            if (!$group->validate($_POST)) {
                $group->getErrors();
                redirect();
            }
            if ($group->update('attribute_group', $id)) {
                $_SESSION['success'] = 'Изменения сохранены';
                redirect();
            }
        }
        $id = !empty($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) {
            throw new \Exception('Страница не найдена', 404);
        }


        $group = (new AttributeGroupModel())->getGroup($id);

        $this->setMeta("Редактирование группы {$group->title}");
        $this->render(compact('group'));
    }

    public function groupAddAction()
    {
        if (!empty($_POST)) {
            $group = new FilterGroup();

            $group->load($_POST);
            if (!$group->validate($_POST)) {
                $group->getErrors();
                redirect();
            }
            if ($group->save('attribute_group', false)) {
                $_SESSION['success'] = 'Группа добавлена';
                redirect();
            }
        }
        $this->setMeta('Новая группа фильтров');
    }

    public function attributeGroupAction()
    {
        $attributesGroup = (new AttributeGroupModel())->getGroups();
        $this->setMeta('Группы фильтров');
        $this->render(compact('attributesGroup'));
    }

    public function attributeAction()
    {
        $attributes = (new AttributeValueModel())->getAttributesValuesAndTitleGroup();
        $this->setMeta('Фильтры');
        $this->render(compact('attributes'));
    }

}