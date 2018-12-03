<?php

namespace app\Models\admin;

use app\Models\AppModel;

class FilterAttr extends AppModel{

    public $attributes = [
        'value' => '',
        'attr_group_id' => '',
    ];

    public $rules = [
        'required' => [
            ['value'],
            ['attr_group_id'],
        ],
        'integer' => [
            ['attr_group_id'],
        ]
    ];

}