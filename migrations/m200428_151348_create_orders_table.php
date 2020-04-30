<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%orders}}`.
 */
class m200428_151348_create_orders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%orders}}', [
            'id' => $this->primaryKey(),
            'customer_id' => $this->integer()->notNull(),
            'status_id' => $this->integer()->defaultValue(1),
            'order_type' => "ENUM('delivery', 'servicing', 'installation')",
            'order_value' => $this->float()->defaultValue(0),
            'schedule_date' => $this->date()->notNull(),
            'street_address' => $this->text()->notNull(),
            'city' => $this->string(50)->notNull(),
            'state_province' => $this->string(50)->notNull(),
            'postal_zip_code' => $this->string(7)->notNull(),
            'country_id' => $this->integer()->notNull(),
            'longitude' => $this->string()->notNull(),
            'latitude' => $this->string()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%orders}}');
    }
}
