<?php

namespace App\Http\Controllers\Admin;

use App\Service\UserService;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Storage;
use DB;

class InquiryController extends AdminController
{
    public function index(Request $request)
    {
        return view('admin.inquiry.index', [
        ]);
    }

    public function detail(Request $request)
    {
        return view('admin.inquiry.detail', [
        ]);
    }

}
