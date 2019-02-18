<?php

namespace app\controllers;

use app\models\TwitterUsers;
use app\models\TwitterUsersForm;
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
        $answer = [
            'error' => 'internal error',
        ];

        $data = [
            'id' => Yii::$app->request->get('id'),
            'user' => Yii::$app->request->get('user'),
            'secret' => Yii::$app->request->get('secret'),
        ];


        $model = new TwitterUsersForm();

        if ($model->load(['TwitterUsersForm' => $data]) && $model->validate()) {
            $model->save(false);
            $answer = [];
        } else {
            if($model->hasErrors()){
                $answer['error'] = reset($model->firstErrors);
            }
        }

        return $answer;
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
                'date' => date('Y-m-d', $user['created_at']),
            ];
        }

        return $answer;
    }

}
