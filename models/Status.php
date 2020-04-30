<?php

namespace app\models;

use app\models\traits\DropDownDataTrait;
use Yii;

/**
 * This is the model class for table "status".
 *
 * @property int $id
 * @property string|null $name
 *
 * @property Orders[] $orders
 */
class Status extends \yii\db\ActiveRecord
{
    use DropDownDataTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'color_code', 'tag'], 'required'],
            [['name', 'color_code', 'tag'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'tag' => 'Tag',
            'color_code' => 'Color Code'
        ];
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery|OrdersQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Order::class, ['status_id' => 'id'])->inverseOf('status');
    }

    /**
     * {@inheritdoc}
     * @return StatusQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new StatusQuery(get_called_class());
    }
}
