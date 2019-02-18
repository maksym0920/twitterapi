<?php

namespace app\controllers;

use app\models\TwitterUsers;
use yii;

class TwitterController extends BaseController
{
    private $userlimit = 50;

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
        ///add?id=...&user=..&secret=..
        // id
        // user
        // secret
        $id = yii::$app->request->get('id');
        $user = yii::$app->request->get('user');
        $secret = yii::$app->request->get('secret');


        return ['action' => 'add'];
    }

    public function actionRemove()
    {
        //GET: {endpoint}/remove?id=...&user=..&secret=..
        return ['action' => 'remove'];
    }

    public function actionFeed()
    {
        //GET: {endpoint}/feed?id=...&secret=..
        return ['action' => 'feed'];
    }

    public function actionUserList()
    {
        $answer = [];

        $users = TwitterUsers::find();

        $countQuery = clone $users;
        $pages = new yii\data\Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize' => $this->userlimit,
            'defaultPageSize' => $this->userlimit,
        ]);

        $users = $users->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();

        foreach ($users as $user) {
            $answer['users'][] = [
                'name' => $user['user'],
                'date' => date('Y-m-d',$user['created_at']),
            ];
        }

        return $answer;
    }

}
