<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%customer}}`.
 */
class m200428_151335_create_customers_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%customers}}', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string(20)->notNull(),
            'last_name' => $this->string(20)->notNull(),
            'email' => $this->string(100)->unique(),
            'phone_number' => $this->string(20)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%customers}}');
    }
}
