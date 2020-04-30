<?php

namespace app\models;

use app\models\traits\DropDownDataTrait;
use Yii;

/**
 * This is the model class for table "countries".
 *
 * @property int $id
 * @property string $country_name
 * @property string $country_code
 *
 * @property Order[] $orders
 */
class Countries extends \yii\db\ActiveRecord
{
    use DropDownDataTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'countries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country_name', 'country_code'], 'required'],
            [['country_name'], 'string', 'max' => 50],
            [['country_code'], 'string', 'max' => 3],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'country_name' => 'Country Name',
            'country_code' => 'Country Code',
        ];
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery|OrderQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::class, ['country_id' => 'id'])->inverseOf('country');
    }

    /**
     * {@inheritdoc}
     * @return CountriesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CountriesQuery(get_called_class());
    }
}
