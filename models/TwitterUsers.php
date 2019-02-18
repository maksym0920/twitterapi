<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%twitter_users}}".
 *
 * @property int $id
 * @property string $user
 * @property int $created_at
 */
class TwitterUsers extends \yii\db\ActiveRecord
{
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
            [['user', 'created_at'], 'required'],
            [['created_at'], 'integer'],
            [['user'], 'string', 'max' => 50],
            [['user'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user' => 'User',
            'created_at' => 'Created At',
        ];
    }
}
