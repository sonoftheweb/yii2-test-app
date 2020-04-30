<?php

use yii\db\Migration;

/**
 * Class m200428_152240_add_foreign_key_constraints
 */
class m200428_152240_add_foreign_key_constraints extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // orders index and f-key to customers
        $this->createIndex(
            'idx-order-customer_id',
            'orders',
            'customer_id'
        );
        $this->addForeignKey(
            'fk-order-customer_id',
            'orders',
            'customer_id',
            'customers',
            'id',
            'CASCADE'
        );

        // orders index and f-key to customers
        $this->createIndex(
            'idx-order-country_id',
            'orders',
            'country_id'
        );
        $this->addForeignKey(
            'fk-order-country_id',
            'orders',
            'country_id',
            'countries',
            'id',
            'CASCADE'
        );

        // orders index and f-key to status
        $this->createIndex(
            'idx-order-status_id',
            'orders',
            'status_id'
        );
        $this->addForeignKey(
            'fk-order-status_id',
            'orders',
            'status_id',
            'status',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-order-country_id',
            'orders'
        );

        $this->dropIndex(
            'idx-order-country_id',
            'orders'
        );

        $this->dropForeignKey(
            'fk-order-customer_id',
            'orders'
        );

        $this->dropIndex(
            'idx-order-customer_id',
            'orders'
        );

        $this->dropForeignKey(
            'fk-order-status_id',
            'orders'
        );

        $this->dropIndex(
            'idx-order-status_id',
            'orders'
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200428_152240_add_foreign_key_constraints cannot be reverted.\n";

        return false;
    }
    */
}
