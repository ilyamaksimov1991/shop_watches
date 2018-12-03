<?php
namespace app\widgets\menu;

use app\Models\AppModel;
use framework\App;
use framework\Cache;

/**
 * Class Menu
 *
 * @package app\widgets\menu
 */
class Menu
{

    protected $data;
    protected $tree;
    protected $menuHtml;
    protected $tpl;
    protected $container = 'ul';
    protected $class = 'menu';
    protected $table = 'category';
    protected $cache = 3600;
    protected $cacheKey = 'ishop_menu';
    protected $attrs = [];
    protected $prepend = '';

    public function __construct($options = [])
    {
        $this->tpl = __DIR__ . '/menu_tpl/menu.php';
        $this->getOptions($options);
        $this->run();
    }

    /**
     * Check the keys with values passed in the array and check with the class properties
     *
     * If the class has such properties, the transferred value is written to it.
     *
     * @param array $options
     */
    private function getOptions($options)
    {
        foreach ($options as $k => $v) {
            if (property_exists($this, $k)) {
                $this->$k = $v;
            }
        }
    }

    /**
     * Run the widget
     */
    private function run()
    {
        /**
         * @var Cache  $cache
         */
        $cache = Cache::instance();
        $this->menuHtml = $cache->get($this->cacheKey);
        if (!$this->menuHtml) {
            $this->data = App::$app->getProperty('cats');
            if(!$this->data){
                $this->data = $cats = (new AppModel())->getDataTable($this->table);
            }
            $this->tree = $this->getTree();
            $this->menuHtml = $this->getMenuHtml($this->tree);
            if ($this->cache) {

                $cache->set($this->cacheKey, $this->menuHtml, $this->cache);
            }
        }
        $this->output();
    }

    /**
     * Get the finished menu
     */
    private function output()
    {
        $attrs = '';
        if (!empty($this->attrs)) {
            foreach ($this->attrs as $k => $v) {
                $attrs .= " $k='$v' ";
            }
        }
        echo "<{$this->container} class='{$this->class}' $attrs>";
        echo $this->prepend;
        echo $this->menuHtml;
        echo "</{$this->container}>";
    }

    /**
     * Get a tree from an associative array
     *
     * @return array
     */
    private function getTree()
    {
        $tree = [];
        $data = $this->data;
        foreach ($data as $id => &$node) {
            if (!$node['parent_id']) {
                $tree[$id] = &$node;
            } else {
                $data[$node['parent_id']]['childs'][$id] = &$node;
            }
        }
        return $tree;
    }

    /**
     * Get menu html
     *
     * @param array $tree
     * @param string $tab
     * @return string
     */
    private function getMenuHtml($tree, $tab = '')
    {
        $str = '';
        foreach ($tree as $id => $category) {
            $str .= $this->catToTemplate($category, $tab, $id);
        }
        return $str;
    }

    /**
     * Template to shape a piece of html category
     *
     * @param array $category
     * @param string $tab
     * @param int $id
     * @return string
     */
    private function catToTemplate($category, $tab, $id)
    {
        ob_start();
        require $this->tpl;
        return ob_get_clean();
    }

}