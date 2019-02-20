<?php

namespace app\controllers;

use app\components\TwitterAPIExchange;
use app\models\TwitterUsersForm;
use yii;

class TwitterController extends BaseController
{
    private $userLimit = 50;
    private $feedCount = 5;

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
        $answer = [
            'error' => 'internal error',
        ];
        $settings = array(
            'oauth_access_token' => "YOUR_OAUTH_ACCESS_TOKEN",
            'oauth_access_token_secret' => "YOUR_OAUTH_ACCESS_TOKEN_SECRET",
            'consumer_key' => "YOUR_CONSUMER_KEY",
            'consumer_secret' => "YOUR_CONSUMER_SECRET"
        );

        $data = [
            'id' => Yii::$app->request->get('id'),
            'secret' => Yii::$app->request->get('secret'),
        ];

        $model = new TwitterUsersForm(['scenario' => TwitterUsersForm::SCENARIO_FEED]);
        if ($model->load(['TwitterUsersForm' => $data]) && $model->validate()) {
            $users = TwitterUsersForm::find()->asArray()->all();
            $feed = [
                'feed' => []
            ];

            try {
                $twitter = new TwitterAPIExchange($settings);
                $twitter = $twitter->buildOauth('https://api.twitter.com/1.1/statuses/user_timeline.json', 'GET');

                foreach ($users as $user) {
                    $params = array(
                        'screen_name' => $user['user'],
                        'count' => $this->feedCount,
                    );

                    $res = $twitter->setGetfield(http_build_query($params))->performRequest();
                    # пример ответа с https://twitsandbox.com/  screen_name = elonmusk & count = 3
                    /*  $res = '[
  {
    "created_at":"Wed Feb 20 04:41:03 +0000 2019",
    "id":1098080063801585664,
    "id_str":"1098080063801585664",
    "text":"Meant to say annualized production rate at end of 2019 probably around 500k, ie 10k cars\/week. Deliveries for year… https:\/\/t.co\/5FnC4Ttg7y",
    "truncated":true,
    "entities":{
      "hashtags":[

      ],
      "symbols":[

      ],
      "user_mentions":[

      ],
      "urls":[
        {
          "url":"https:\/\/t.co\/5FnC4Ttg7y",
          "expanded_url":"https:\/\/twitter.com\/i\/web\/status\/1098080063801585664",
          "display_url":"twitter.com\/i\/web\/status\/1…",
          "indices":[
            116,
            139
          ]
        }
      ]
    },
    "source":"<a href=\"http:\/\/twitter.com\/download\/iphone\" rel=\"nofollow\">Twitter for iPhone<\/a>",
    "in_reply_to_status_id":1098013283372589056,
    "in_reply_to_status_id_str":"1098013283372589056",
    "in_reply_to_user_id":44196397,
    "in_reply_to_user_id_str":"44196397",
    "in_reply_to_screen_name":"elonmusk",
    "user":{
      "id":44196397,
      "id_str":"44196397"
    },
    "geo":null,
    "coordinates":null,
    "place":null,
    "contributors":null,
    "is_quote_status":false,
    "retweet_count":1120,
    "favorite_count":30623,
    "favorited":false,
    "retweeted":false,
    "lang":"en"
  },
  {
    "created_at":"Wed Feb 20 00:17:02 +0000 2019",
    "id":1098013621924184064,
    "id_str":"1098013621924184064",
    "text":"@kristenhenry55 Won’t be long before they do",
    "truncated":false,
    "entities":{
      "hashtags":[

      ],
      "symbols":[

      ],
      "user_mentions":[
        {
          "screen_name":"kristenhenry55",
          "name":"Kristen Henry",
          "id":34135802,
          "id_str":"34135802",
          "indices":[
            0,
            15
          ]
        }
      ],
      "urls":[

      ]
    },
    "source":"<a href=\"http:\/\/twitter.com\/download\/iphone\" rel=\"nofollow\">Twitter for iPhone<\/a>",
    "in_reply_to_status_id":1098013491640754176,
    "in_reply_to_status_id_str":"1098013491640754176",
    "in_reply_to_user_id":34135802,
    "in_reply_to_user_id_str":"34135802",
    "in_reply_to_screen_name":"kristenhenry55",
    "user":{
      "id":44196397,
      "id_str":"44196397"
    },
    "geo":null,
    "coordinates":null,
    "place":null,
    "contributors":null,
    "is_quote_status":false,
    "retweet_count":36,
    "favorite_count":829,
    "favorited":false,
    "retweeted":false,
    "lang":"en"
  },
  {
    "created_at":"Wed Feb 20 00:15:41 +0000 2019",
    "id":1098013283372589056,
    "id_str":"1098013283372589056",
    "text":"Tesla made 0 cars in 2011, but will make around 500k in 2019",
    "truncated":false,
    "entities":{
      "hashtags":[

      ],
      "symbols":[

      ],
      "user_mentions":[

      ],
      "urls":[

      ]
    },
    "source":"<a href=\"http:\/\/twitter.com\/download\/iphone\" rel=\"nofollow\">Twitter for iPhone<\/a>",
    "in_reply_to_status_id":1098009983931707393,
    "in_reply_to_status_id_str":"1098009983931707393",
    "in_reply_to_user_id":44196397,
    "in_reply_to_user_id_str":"44196397",
    "in_reply_to_screen_name":"elonmusk",
    "user":{
      "id":44196397,
      "id_str":"44196397"
    },
    "geo":null,
    "coordinates":null,
    "place":null,
    "contributors":null,
    "is_quote_status":false,
    "retweet_count":8256,
    "favorite_count":109675,
    "favorited":false,
    "retweeted":false,
    "lang":"en"
  }
]';*/
                    $res = json_decode($res, true);

                    if (!empty($res['errors'])) {
                        // если есть ошибка просто пропускаем
                        continue;
                    }

                    foreach ($res as $item) {
                        $feed['feed'][] = [
                            'user' => $user['user'],
                            'tweet' => $item['text'],
                            'hashtag' => $item['entities']['hashtags'],
                        ];
                    }
                }
            } catch (\Exception $e) {
            }

            if (!empty($feed['feed'])) {
                $answer = $feed;
            }
        } else {
            if ($model->hasErrors()) {
                $answer['error'] = reset($model->firstErrors);
            }
        }

        return $answer;
    }
/*
    public function actionUserList()
    {
        $answer = [];

        $users = TwitterUsersForm::find();

        $countQuery = clone $users;
        $pages = new yii\data\Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize' => $this->userLimit,
            'defaultPageSize' => $this->userLimit,
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
*/
}
