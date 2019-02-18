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
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }

    public function actionIndex()
    {
        return ['action' => ''];
    }

    public function actionAdd()
    {
        $answer = [
            'error' => 'internal error',
        ];

        $data = [
            'id' => Yii::$app->request->get('id'),
            'user' => Yii::$app->request->get('user'),
            'secret' => Yii::$app->request->get('secret'),
        ];

        $model = new TwitterUsersForm(['scenario' => TwitterUsersForm::SCENARIO_ADD]);

        if ($model->load(['TwitterUsersForm' => $data]) && $model->validate()) {
            $model->save(false);
            $answer = [];
        } else {
            if ($model->hasErrors()) {
                $answer['error'] = reset($model->firstErrors);
            }
        }

        return $answer;
    }

    public function actionRemove()
    {
        $answer = [
            'error' => 'internal error',
        ];

        $data = [
            'id' => Yii::$app->request->get('id'),
            'user' => Yii::$app->request->get('user'),
            'secret' => Yii::$app->request->get('secret'),
        ];

        $model = new TwitterUsersForm(['scenario' => TwitterUsersForm::SCENARIO_REMOVE]);
        if ($model->load(['TwitterUsersForm' => $data]) && $model->validate()) {
            $user = TwitterUsersForm::find()->where(['user' => $data['user']])->one();
            if ($user) {
                $user->delete();
                $answer = [];
            }
        } else {
            if ($model->hasErrors()) {
                $answer['error'] = reset($model->firstErrors);
            }
        }

        return $answer;
    }

    public function actionFeed()
    {
        //GET: {endpoint}/feed?id=...&secret=..
        $answer = [
            'error' => 'internal error',
        ];

        $data = [
            'id' => Yii::$app->request->get('id'),
            'secret' => Yii::$app->request->get('secret'),
        ];

        $model = new TwitterUsersForm(['scenario' => TwitterUsersForm::SCENARIO_FEED]);
        if ($model->load(['TwitterUsersForm' => $data]) && $model->validate()) {
            $users = TwitterUsersForm::find()->asArray()->all();

            foreach ($users as $user) {

            }
            var_dump($users);

        } else {
            if ($model->hasErrors()) {
                $answer['error'] = reset($model->firstErrors);
            }
        }

        return $answer;
    }

    public function actionUserList()
    {
        $answer = [];

        $users = TwitterUsersForm::find();

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
