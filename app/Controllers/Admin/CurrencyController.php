<?php

namespace app\Controllers\Admin;

use app\Models\admin\Currency;
use app\Models\CurrencyModel;

/**
 * Class CurrencyController
 * @package app\controllers\admin
 */
class CurrencyController extends AppController
{
    public function indexAction()
    {
        $currencies = (new CurrencyModel())->getAllCurrencies();

        $this->setMeta('Валюты магазина');
        $this->render(compact('currencies'));
    }

    public function deleteAction()
    {
        $id = !empty($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) {
            throw new \Exception('Страница не найдена', 404);
        }

        (new CurrencyModel())->deleteCurrency($id);
        $_SESSION['success'] = "Изменения сохранены";
        redirect();
    }

    public function editAction()
    {
        if (!empty($_POST)) {
            $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;

            if (!$id) {
                throw new \Exception('Страница не найдена', 404);
            }
            $currency = new Currency();

            $currency->load($_POST);
            $currency->attributes['base'] = $currency->attributes['base'] ? '1' : '0';
            if (!$currency->validate($_POST)) {
                $currency->getErrors();
                redirect();
            }
            if ($currency->update('currency', $id)) {
                $_SESSION['success'] = "Изменения сохранены";
                redirect();
            }
        }

        $id = !empty($_GET['id']) ? (int)$_GET['id'] : null;
        if (!$id) {
            throw new \Exception('Страница не найдена', 404);
        }

        $currency = (new CurrencyModel())->getCurrency(['id' => $id]);
        $this->setMeta("Редактирование валюты {$currency->title}");
        $this->render(compact('currency'));
    }

    public function addAction()
    {
        if (!empty($_POST)) {
            $currency = new Currency();

            $currency->load($_POST);
            $currency->attributes['base'] = $currency->attributes['base'] ? '1' : '0';
            if (!$currency->validate($_POST)) {
                $currency->getErrors();
                redirect();
            }
            if ($currency->save('currency')) {
                $_SESSION['success'] = 'Валюта добавлена';
                redirect();
            }
        }
        $this->setMeta('Новая валюта');
    }
}
