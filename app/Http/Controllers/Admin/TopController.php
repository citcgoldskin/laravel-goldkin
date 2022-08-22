<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Service\UserService;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Session;
use Storage;
use DB;

class TopController extends AdminController
{
    public function index(Request $request)
    {
        return view('admin.top.index', [
        ]);
    }

}
