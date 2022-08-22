<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Session;


class LessonAccessHistoryController extends Controller
{
    public function index(Request $request)
    {
        return view('user.lesson.search', []);
    }
}
