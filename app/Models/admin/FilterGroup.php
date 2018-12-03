<?php

namespace app\Models\admin;

use app\Models\AppModel;

class FilterGroup extends AppModel{

    public $attributes = [
        'title' => '',
    ];

    public $rules = [
        'required' => [
            ['title'],
        ],
    ];

}