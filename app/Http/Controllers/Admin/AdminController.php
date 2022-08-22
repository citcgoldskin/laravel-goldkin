<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Auth;

class AdminController extends Controller
{
    protected $platform = 'admin';
    protected $per_page = 20;

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}
