<?php

namespace app\models\traits;

trait DropDownDataTrait
{
    public static function listModel($indexable, $select = ['id'])
    {
        return self::find()
            ->select($select)
            ->indexBy($indexable)
            ->asArray()
            ->column();
    }
}