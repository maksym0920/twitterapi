<?php

namespace app\controllers;


/**
 * Site controller
 */
class BaseController extends \yii\web\Controller
{
    /* not fount 404 */
    public function actionError()
    {
        return $this->render('error.twig');
    }

}
