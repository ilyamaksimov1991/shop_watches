<?php
namespace app\models\admin;

use app\models\AppModel;
use app\models\AttributeProductModel;
use app\models\GalleryModel;
use app\models\RelatedProductModel;

/**
 * Class Product
 * @package app\models\admin
 */
class Product extends AppModel
{

    public $attributes = [
        'title' => '',
        'category_id' => '',
        'keywords' => '',
        'description' => '',
        'price' => '',
        'old_price' => '',
        'content' => '',
        'status' => '',
        'hit' => '',
        'alias' => '',
    ];

    public $rules = [
        'required' => [
            ['title'],
            ['category_id'],
            ['price'],
        ],
        'integer' => [
            ['category_id'],
        ],
    ];

    public function editRelatedProduct($id, $data)
    {
        $relatedProduct = new RelatedProductModel();
        $relatedProduct->getColumn('related_id', ['product_id' => $id]);

        // если менеджер убрал связанные товары - удаляем их
        if (empty($data['related']) && !empty($relatedProduct)) {
            $relatedProduct->delete(['product_id' => $id]);
            return;
        }
        // если добавляются связанные товары
        if (empty($relatedProduct) && !empty($data['related'])) {
            $sql_part = '';
            foreach ($data['related'] as $v) {
                $v = (int)$v;
                $sql_part .= "($id, $v),";
            }
            $sql_part = rtrim($sql_part, ',');

            (new RelatedProductModel())->getRelatedProducts($sql_part);
            return;
        }
        // если изменились связанные товары - удалим и запишем новые
        if (!empty($data['related'])) {
            $result = array_diff($relatedProduct, $data['related']);
            if (!empty($result) || count($relatedProduct) != count($data['related'])) {

                $relatedProduct->delete(['product_id' => $id]);
                $sql_part = '';
                foreach ($data['related'] as $v) {
                    $v = (int)$v;
                    $sql_part .= "($id, $v),";
                }
                $sql_part = rtrim($sql_part, ',');
                (new RelatedProductModel())->getRelatedProducts($sql_part);
            }
        }
    }

    public function editFilter($id, $data)
    {
        $attributeProduct = new AttributeProductModel();
        $filter = $attributeProduct->getColumn('attr_id', ['product_id' => $id]);

        // если менеджер убрал фильтры - удаляем их
        if (empty($data['attrs']) && !empty($filter)) {
            $attributeProduct->delete(['product_id' => $id]);
            return;
        }
        // если фильтры добавляются
        if (empty($filter) && !empty($data['attrs'])) {
            $sql_part = '';
            foreach ($data['attrs'] as $v) {
                $sql_part .= "($v, $id),";
            }
            $sql_part = rtrim($sql_part, ',');
            $attributeProduct->addAttibuteProduct($sql_part);
            return;
        }
        // если изменились фильтры - удалим и запишем новые
        if (!empty($data['attrs'])) {
            $result = array_diff($filter, $data['attrs']);
            if (!$result || count($filter) != count($data['attrs'])) {
                $attributeProduct->delete(['product_id' => $id]);
                $sql_part = '';
                foreach ($data['attrs'] as $v) {
                    $sql_part .= "($v, $id),";
                }
                $sql_part = rtrim($sql_part, ',');
                $attributeProduct->addAttibuteProduct($sql_part);
            }
        }
    }

    public function getImg()
    {
        if (!empty($_SESSION['single'])) {
            $this->attributes['img'] = $_SESSION['single'];
            unset($_SESSION['single']);
        }
    }

    public function saveGallery($id)
    {
        if (!empty($_SESSION['multi'])) {
            $sql_part = '';
            foreach ($_SESSION['multi'] as $v) {
                $sql_part .= "('$v', $id),";
            }
            $sql_part = rtrim($sql_part, ',');
            (new GalleryModel())->addImagesGallery($sql_part);
            unset($_SESSION['multi']);
        }
    }

    /**
     * @param int $name
     * @param int $wmax
     * @param int $hmax
     */
    public function uploadImg($name, $wmax, $hmax)
    {
        $uploaddir = WWW . '/images/';
        $ext = strtolower(preg_replace("#.+\.([a-z]+)$#i", "$1", $_FILES[$name]['name'])); // расширение картинки
        $types = array("image/gif", "image/png", "image/jpeg", "image/pjpeg", "image/x-png"); // массив допустимых расширений
        if ($_FILES[$name]['size'] > 1048576) {
            $res = array("error" => "Ошибка! Максимальный вес файла - 1 Мб!");
            exit(json_encode($res));
        }
        if ($_FILES[$name]['error']) {
            $res = array("error" => "Ошибка! Возможно, файл слишком большой.");
            exit(json_encode($res));
        }
        if (!in_array($_FILES[$name]['type'], $types)) {
            $res = array("error" => "Допустимые расширения - .gif, .jpg, .png");
            exit(json_encode($res));
        }
        $new_name = md5(time()) . ".$ext";
        $uploadfile = $uploaddir . $new_name;
        if (@move_uploaded_file($_FILES[$name]['tmp_name'], $uploadfile)) {
            if ($name == 'single') {
                $_SESSION['single'] = $new_name;
            } else {
                $_SESSION['multi'][] = $new_name;
            }
            self::resize($uploadfile, $uploadfile, $wmax, $hmax, $ext);
            $res = array("file" => $new_name);
            exit(json_encode($res));
        }
    }

    /**
     * @param string $target путь к оригинальному файлу
     * @param string $dest путь сохранения обработанного файла
     * @param string $wmax максимальная ширина
     * @param string $hmax максимальная высота
     * @param string $ext расширение файла
     */
    public static function resize($target, $dest, $wmax, $hmax, $ext)
    {
        list($w_orig, $h_orig) = getimagesize($target);
        $ratio = $w_orig / $h_orig; // =1 - квадрат, <1 - альбомная, >1 - книжная

        if (($wmax / $hmax) > $ratio) {
            $wmax = $hmax * $ratio;
        } else {
            $hmax = $wmax / $ratio;
        }

        $img = "";
        switch ($ext) {
            case("gif"):
                $img = imagecreatefromgif($target);
                break;
            case("png"):
                $img = imagecreatefrompng($target);
                break;
            default:
                $img = imagecreatefromjpeg($target);
        }
        $newImg = imagecreatetruecolor($wmax, $hmax); // создаем оболочку для новой картинки

        if ($ext == "png") {
            imagesavealpha($newImg, true); // сохранение альфа канала
            $transPng = imagecolorallocatealpha($newImg, 0, 0, 0, 127); // добавляем прозрачность
            imagefill($newImg, 0, 0, $transPng); // заливка
        }

        imagecopyresampled($newImg, $img, 0, 0, 0, 0, $wmax, $hmax, $w_orig, $h_orig); // копируем и ресайзим изображение
        switch ($ext) {
            case("gif"):
                imagegif($newImg, $dest);
                break;
            case("png"):
                imagepng($newImg, $dest);
                break;
            default:
                imagejpeg($newImg, $dest);
        }
        imagedestroy($newImg);
    }

}