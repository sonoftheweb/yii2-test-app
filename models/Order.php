<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property int $customer_id
 * @property string $schedule_date
 * @property string $street_address
 * @property string $city
 * @property string $state_province
 * @property string $postal_zip_code
 * @property int $country_id
 *
 * @property Countries $country
 * @property Customer $customer
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    public function fields()
    {
        return parent::fields();
    }

    public function extraFields()
    {
        return ['status', 'customer'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['schedule_date', 'street_address', 'city', 'state_province', 'country_id', 'order_value', 'order_type', 'latitude', 'longitude'], 'required'],
            [['customer_id', 'country_id', 'status_id'], 'integer'],
            [['schedule_date'], 'safe'],
            [['street_address'], 'string'],
            [['city', 'state_province'], 'string', 'max' => 50],
            [['postal_zip_code'], 'string', 'max' => 7],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Countries::class, 'targetAttribute' => ['country_id' => 'id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Countries::class, 'targetAttribute' => ['customer_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'order_value' => 'Order Value',
            'status_id' => 'Status',
            'schedule_date' => 'Schedule Date',
            'street_address' => 'Street Address',
            'city' => 'City',
            'state_province' => 'State / Province',
            'postal_zip_code' => 'Postal / Zip Code',
            'country_id' => 'Country ID'
        ];
    }

    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery|CountryQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Countries::class, ['id' => 'country_id'])->inverseOf('orders');
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery|CustomerQuery
     */
    public function getCustomer()
    {
        return $this->hasOne(Customer::class, ['id' => 'customer_id'])->inverseOf('orders');
    }

    public function getStatus()
    {
        return $this->hasOne(Status::class, ['id' => 'status_id'])->inverseOf('orders');
    }

    /**
     * {@inheritdoc}
     * @return OrdersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OrdersQuery(get_called_class());
    }
}
