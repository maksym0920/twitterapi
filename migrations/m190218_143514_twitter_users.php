<?php

use yii\db\Migration;

/**
 * Class m190218_143514_twitter_users
 */
class m190218_143514_twitter_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%twitter_users}}', [
            'id' => $this->primaryKey(),
            'user' => $this->string(50)->notNull()->unique(),
            'created_at' => $this->integer()->notNull(),
        ], $tableOptions);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%twitter_users}}');
    }

}
