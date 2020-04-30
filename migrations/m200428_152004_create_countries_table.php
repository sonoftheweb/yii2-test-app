<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%country}}`.
 */
class m200428_152004_create_countries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%countries}}', [
            'id' => $this->primaryKey(),
            'country_name' => $this->string(50)->unique()->notNull(),
            'country_code' => $this->string(3)->unique()->notNull()
        ]);

        $countries = [
            ['Canada', 'CA'],
            ['United States', 'US'],
            ['Mexico', 'MX'],
        ];

        Yii::$app
            ->db
            ->createCommand()
            ->batchInsert('countries', ['country_name', 'country_code'], $countries)
            ->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%countries}}');
    }
}
