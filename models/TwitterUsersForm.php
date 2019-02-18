<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class TwitterUsersForm extends \yii\db\ActiveRecord
{
    public $id;
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
            [['user'], 'string', 'max' => 50, 'message' => 'invalid user'], //можно добавить проверку в твитерре
            [['user'], 'unique', 'message' => 'user already exists'],
            ['secret', 'secretValidate'],
        ];
    }

    public function secretValidate($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $curSecret = sha1($this->id . $this->user);

            if ($this->secret !== $curSecret) {
                $this->addError($attribute, 'access denied');
            }
        }
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }

}
