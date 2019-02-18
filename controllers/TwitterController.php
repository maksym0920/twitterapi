<?php

namespace app\controllers;

class TwitterController extends BaseController
{
    public function actions()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }

    public function actionIndex()
    {
        return ['action' => ''];
    }
    public function actionAdd()
    {
        return ['action' => 'add'];
    }
    public function actionRemove()
    {
        return ['action' => 'remove'];
    }
    public function actionFeed()
    {
        return ['action' => 'feed'];
    }
    public function actionUserList()
    {
        return ['action' => 'user-list'];
    }

}
