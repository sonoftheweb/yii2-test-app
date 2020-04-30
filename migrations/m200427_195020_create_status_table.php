<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%status}}`.
 */
class m200427_195020_create_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%status}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'tag' => $this->string(),
            'color_code' => $this->string()
        ]);

        $statuses = [
            ['Pending', 'pending', 'light'],
            ['Assigned', 'assigned', 'primary'],
            ['On Route', 'on_route', 'warning'],
            ['Done', 'done', 'success'],
            ['Cancelled', 'cancelled', 'danger']
        ];

        Yii::$app
            ->db
            ->createCommand()
            ->batchInsert('status', ['name', 'tag', 'color_code'], $statuses)
            ->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%status}}');
    }
}
