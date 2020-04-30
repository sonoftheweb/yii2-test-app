<?php

namespace app\helpers;

class ApplicationHelpers
{
    public static function orderTypes()
    {
        return [
            'delivery' => 'Delivery',
            'servicing' => 'Servicing',
            'installation' => 'Installation'
        ];
    }
}