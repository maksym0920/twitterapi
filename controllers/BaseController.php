<?php

namespace app\controllers;


/**
 * Site controller
 */
class BaseController extends \yii\web\Controller
{
    public function actionError()
    {
        return ['status'=>'404'];
    }

}
