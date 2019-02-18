<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class TwitterUsersForm extends \yii\db\ActiveRecord
{
    const SCENARIO_ADD = 'add';
    const SCENARIO_REMOVE = 'remove';
    const SCENARIO_FEED = 'feed';

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
            [['id', 'secret'], 'required', 'message' => 'missing parameter'],
            ['user', 'required', 'message' => 'missing parameter', 'on' => [self::SCENARIO_ADD, self::SCENARIO_REMOVE]],
            [['id'], 'string', 'length' => [32, 32], 'message' => 'access denied'],
            [
                ['user'],
                'string',
                'max' => 50,
                'message' => 'invalid user',
                'on' => [self::SCENARIO_ADD, self::SCENARIO_REMOVE]
            ], //можно добавить проверку в твитерре
            ['secret', 'secretValidate'],
            [['user'], 'unique', 'message' => 'user already exists', 'on' => self::SCENARIO_ADD],
            [['user'], 'exist', 'message' => 'user not exist', 'on' => self::SCENARIO_REMOVE],
            ['user', 'default', 'value' => '', 'on' => self::SCENARIO_FEED],
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
