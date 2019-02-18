<?php

namespace app\models;

use Yii;

class TwitterUsersForm extends \yii\db\ActiveRecord
{
    public $id;
    public $user;
    public $secret;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%twitter_users}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user', 'secret'], 'required', 'message' => 'missing parameter'], //{attribute}

            [['id'], 'string', 'length' => [32, 32], 'message' => 'access denied'],
            [['user'], 'string', 'max' => '50', 'message' => 'invalid user'], //проверка в твитерре?
            [['user'], 'unique', 'message' => 'user already exists'],
            ['']
        ];
    }

}
