<?php
namespace framework;

use app\models\CategoryModel;

/**
 * Class Cache
 * @package framework
 */
class Cache
{
    /**
     * @see SingletonTrait
     */
    use SingletonTrait;

    /**
     * Get file with cache
     *
     * @param string $key
     * @return bool
     */
    public static function get($key)
    {
        $fileCash = CASHE . '/' . md5($key) . '.txt';
        if (file_exists($fileCash)) {
            $content = unserialize(file_get_contents($fileCash));
            if (time() <= $content['end_time']) {
                return $content['data'];
            }

            unlink($fileCash);
        }
        return false;
    }

    /**
     * Create a file with cache
     *
     * @param string $key
     * @param string $data
     * @param int $seconds
     * @return bool
     */
    public static function set($key, $data, $seconds = 3600)
    {
        if ($seconds) {
            $content['data'] = $data;
            $content['end_time'] = time() + $seconds;

            if (file_put_contents(CASHE . '/' . md5($key) . '.txt', serialize($content))) {
                return true;
            }

        }
        return false;

    }

    /**
     * Delete file with cache
     *
     * @param string $key
     */
    public static function delete($key)
    {
        $fileCash = CASHE . '/' . md5($key) . '.txt';
        if (file_exists($fileCash)) {
            unlink($fileCash);
        }
    }

    /**
     * Write to the menu category cache
     *
     * @return array
     */
    public static function cacheCategory()
    {
        /**
         * @var Cache $cache
         */
        $cache = Cache::instance();
        $cats = $cache->get('cats');
        if (!$cats) {
            $cats = (new CategoryModel())->getCategories();
            $cache->set('cats', $cats);
        }
        return $cats;
    }


    /**
     * @param string $key
     */
    public function deleteCache($key)
    {
        switch($key){
            case 'category':
                self::delete('cats');
                self::delete('ishop_menu');
                break;
            case 'filter':
                self::delete('filter_group');
                self::delete('filter_attrs');
                break;
        }

    }

}