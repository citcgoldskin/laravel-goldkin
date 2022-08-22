<?php

namespace App\Service;
use App\Models\Contact;

class ContactService
{

    public static function doCreateContact($params)
    {
        return Contact::create($params);
    }

}
