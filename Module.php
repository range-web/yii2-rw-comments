<?php

namespace rangeweb\comments;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'rangeweb\comments\controllers';
    public $objectsTitle = [];
    public $deleteMode = 'delete';
    
    public function init()
    {
        parent::init();
    }
    
}
