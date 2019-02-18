<?php

namespace app\controllers;


class BaseController extends \yii\web\Controller
{
    public function actionError()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['error' => 'internal error'];
    }

}
