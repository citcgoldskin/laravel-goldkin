<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\GuidRequest;
use App\Models\MainVisual;
use App\Service\MainVisualService;
use App\Service\SettingService;
use Illuminate\Http\Request;
use Session;
use Storage;
use DB;
use Auth;
use Validator;


class MasterController extends AdminController
{
    public function index(Request $request)
    {
        return view('admin.master.index', [

        ]);
    }

    public function setCost(Request $request)
    {
        $filed = $request->input('field');
        $validator = Validator::make($request->all(), [
            'cost' => 'required|numeric|min:0'
        ], [
            'cost.required' => '数値を入力してください。',
            'cost.numeric' => '数値を入力してください。',
            'cost.min' => '0以上の数値を入力してください。'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'result_code' => 'failed',
                'errors' => $validator->getMessageBag()->toArray()
            ]);
        }

        $cost = $request->input('cost');

        SettingService::setSetting($filed, $cost, 'float');

        return response()->json([
            'result_code' => 'success',
        ]);
    }

    public function setReviews(Request $request)
    {
        $params = $request->except('_token');
        $rules = [];
        $messages = [];
        foreach ($params as $key => $value) {
            $rules[$key] = 'required|numeric|min:0';

            $messages[$key.'.required'] = '数値を入力してください。';
            $messages[$key.'.numeric'] = '数値を入力してください。';
            $messages[$key.'.min'] = '0以上の数値を入力してください。';
        }
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'result_code' => 'failed',
                'errors' => $validator->getMessageBag()->toArray()
            ]);
        }

        foreach ($params as $key => $value) {
            SettingService::setSetting($key, $value, 'float');
        }

        return response()->json([
            'result_code' => 'success',
        ]);
    }

    public function mainVisual(Request $request, $type)
    {
        if(! array_key_exists($type, config('const.main_visuals'))){
            abort(404);
        }
        $page = $request->input('page',1);
        $main_visuals = MainVisualService::doSearch($type)->paginate(1);
        return view('admin.master.main_visual', [
            'main_visuals' => $main_visuals,
            'page' => $page,
            'type' => $type
        ]);
    }

    public function saveGuid(GuidRequest $request, $type)
    {
        if(! array_key_exists($type, config('const.main_visuals'))){
            abort(404);
        }

        $params = $request->only([
            'description',
            'file_path',
            'link'
        ]);
        $params['type'] = $type;
        $page = $request->input('page', 1);
        if($request->input('id')) {
            $obj_visual = MainVisual::find($request->input('id'));
            MainVisualService::doUpdate($obj_visual, $params);
        } else {
            MainVisualService::doCreate($params);
        }

        return redirect()->route('admin.master.main_visual', ['type'=>$type, 'page'=>$page]);
    }

    public function removeVisualPage(Request $request, MainVisual $main_visual)
    {
        if(is_object($main_visual)) {
            $type = $main_visual->type;
            $page = $request->input('page', 1);
            $main_visual->delete();
            return redirect()->route('admin.master.main_visual', ['type'=>$type, 'page'=>$page]);
        } else {
            abort(404);
        }
    }

    public function clearVisual(Request $request, $type)
    {
        MainVisual::where('type', $type)->delete();
        return redirect()->route('admin.master.main_visual', ['type'=>$type, 'page'=>1]);
    }

    public function uploadFile(Request $request)
    {
        $file = $request->file('vfile');
        request()->validate([
            'vfile' => 'max:524288', //512M
        ]);
        $real_name = $file->getClientOriginalName();
        $path_name = "main_visual_".date("YmdHis"). '.'. $file->getClientOriginalExtension();
        $file->storeAs('public/temp', $path_name);
        return response()->json([
            'name' => $real_name,
            'path' => $path_name,
        ]);
    }

    public function deleteFile(Request $request)
    {
        $file_path = $request->input('file_path', '');
        $type = $request->input('type', '');

        if($file_path) {
            if($type){
                if(Storage::disk('main_visual')->exists($type . '/' .$file_path)){
                    Storage::disk('main_visual')->delete($type. '/' .$file_path);
                    return response()->json(['result_code'=>'success']);
                }
            } else {
                Storage::disk('temp')->delete($file_path);
                return response()->json(['result_code'=>'success']);
            }
        }

        return response()->json(['result_code'=>'success']);
    }


    public function textMaster(Request $request, $type)
    {
        if(! array_key_exists($type, config('const.text_contents'))){
            abort(404);
        }

        $main_visual = MainVisual::where('type', $type)->first();
        return view('admin.master.text_master', [
            'main_visual' => $main_visual,
            'type' => $type
        ]);
    }

    public function saveText(Request $request, $type)
    {
        if(! array_key_exists($type, config('const.text_contents'))){
            abort(404);
        }

        $params = $request->only([
            'description',
        ]);
        $params['type'] = $type;

        if($request->input('id')) {
            $obj_visual = MainVisual::find($request->input('id'));
            MainVisualService::doUpdate($obj_visual, $params);
        } else {
            MainVisualService::doCreate($params);
        }

        return redirect()->route('admin.master.text_master', ['type'=>$type]);
    }

}
