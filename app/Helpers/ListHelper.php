<?php

namespace App\Helpers;

Class ListHelper
{
    public static function getTables()
    {
        return [
            'contacts',
            'contact_lists',
            'templates',
            'fields',
            'messages',
            'broadcasts'
        ];
    }
}
