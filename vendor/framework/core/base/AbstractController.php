<?php

namespace framework\base;

/**
 * Class AbstractController
 *
 * @package framework\base
 */
abstract class AbstractController
{
    /**
     * @var array $route
     */
    public $route;
    /**
     * @var string $controller
     */
    public $controller;
    /**
     * @var string $model
     */
    public $model;
    /**
     * @var string $view
     */
    public $view;
    /**
     * @var string $layout
     */
    public $layout;
    /**
     * @var string $prefix
     */
    public $prefix;
    /**
     * @var array $data data passed to the view
     */
    public $data = [];
    /**
     * @var array $meta meta data passed to the view
     */
    public $meta = ['title' => '', 'deck' => '', 'keywords' => ''];

    public function __construct($route)
    {
        $this->route = $route;
        $this->controller = $route['controller'];
        $this->view = $route['action'];
        $this->prefix = $route['prefix'];

    }

    /**
     * Set data
     *
     * Data passed to the view
     *
     * @param $data
     */
    public function render($data)
    {
        $this->data = $data;
    }

    /**
     * Add meta information
     *
     * Meta data passed to the view
     *
     * @param string $title
     * @param string $deck
     * @param string $keywords
     */
    public function setMeta($title = '', $desc = '', $keywords = '')
    {
        $this->meta['title'] = $title;
        $this->meta['desc'] = $desc;
        $this->meta['keywords'] = $keywords;
    }

    /**
     * Connects view
     */
    public function getView()
    {
        $viewObject = new View($this->route, $this->layout, $this->view, $this->meta);
        $viewObject->render($this->data);
    }

    /**
     * Check Ajax
     *
     * @return bool
     */
    public function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    /**
     * Load view
     *
     * @param $view
     * @param array $vars
     */
    public function loadView($view, $vars = [])
    {
        extract($vars);
        require APP . "/views/{$this->prefix}{$this->controller}/{$view}.php";
        die;
    }

}