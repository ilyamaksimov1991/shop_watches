<?php
namespace app\widgets\filter;

use app\models\AttributeGroupModel;
use app\models\AttributeValueModel;
use framework\Cache;

/**
 * Class Filter
 * @package app\widgets\filter
 */
class Filter
{
    public $groups;
    public $attributes;
    public $tpl;
    public $filter;

    public function __construct($filter = null, $tpl = ''){
        $this->filter = $filter;
        $this->tpl = $tpl ?: __DIR__ . '/filter_tpl.php';
        $this->run();
    }

    /**
     * If the user selects the product filter then add sql query filter
     *
     * @return string
     */
    public static function getSqlQueryIdProductsThatSatisfyFiltert()
    {
        $sql_part = '';
        if (!empty($_GET['filter'])) {

            $filter = self::getFilter();
            if ($filter) {
                $cnt = self::getCountGroups($filter);
                $sql_part = "AND id IN (SELECT product_id FROM attribute_product WHERE attr_id IN ($filter) GROUP BY product_id HAVING COUNT(product_id) = $cnt)";
            }
        }
        return $sql_part;
    }

    /**
     * @return mixed|null|string
     */
    private static function getFilter()
    {
        $filter = null;
        if (!empty($_GET['filter'])) {
            $filter = preg_replace("#[^\d,]+#", '', $_GET['filter']);
            $filter = trim($filter, ',');
        }
        return $filter;
    }

    /**
     * @param string $filter
     * @return int
     */
    private static function getCountGroups($filter)
    {
        $filters = explode(',', $filter);

        /**
         * @var Cache $cache
         */
        $cache = Cache::instance();
        $attrs = $cache->get('filter_attrs');
        if (!$attrs) {
            $attrs = self::getAttributesByGroups();
        }
        $data = [];
        foreach ($attrs as $key => $item) {
            foreach ($item as $k => $v) {
                if (in_array($k, $filters)) {
                    $data[] = $key;
                    break;
                }
            }
        }
        return count($data);
    }

    /**
     * Run widgets
     */
    private function run()
    {
        /**
         * @var Cache $cache
         */
        $cache = Cache::instance();
        $this->groups = $cache->get('filter_group');
        if (!$this->groups) {
            $this->groups = (new AttributeGroupModel())->getGroups();
            $cache->set('filter_group', $this->groups, 30);
        }
        $this->attributes = $cache->get('filter_attrs');
        if (!$this->attributes) {
            $this->attributes = self::getAttributesByGroups();
            $cache->set('filter_attrs', $this->attributes, 30);
        }
        $filters = $this->getHtml();
        echo $filters;
    }

    /**
     * @return string
     */
    private function getHtml()
    {
        ob_start();
        $filter = self::getFilter();
        if (!empty($filter)) {
            $filter = explode(',', $filter);
        }
        require $this->tpl;
        return ob_get_clean();
    }

    /**
     * @return array
     */
    private static function getAttributesByGroups()
    {
        $data = (new AttributeValueModel())->getAttributesValue();
        $attributes = [];
        foreach ($data as $k => $v) {
            $attributes[$v['attr_group_id']][$k] = $v['value'];
        }
        return $attributes;
    }


}