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
            'color_code' => $this->string()
        ]);

        $statuses = [
            ['Pending', 'light'],
            ['Assigned', 'primary'],
            ['On Route', 'warning'],
            ['Done', 'success'],
            ['Cancelled', 'danger']
        ];

        Yii::$app
            ->db
            ->createCommand()
            ->batchInsert('status', ['name', 'color_code'], $statuses)
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
