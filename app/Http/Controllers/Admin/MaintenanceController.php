<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\MaintenanceRequest;
use App\Models\Maintenance;
use App\Service\MaintenanceService;
use Illuminate\Http\Request;
use Session;
use Storage;
use DB;
use Auth;
use Validator;
use Carbon;

class MaintenanceController extends AdminController
{
    public function index(Request $request)
    {
        $maintenances = MaintenanceService::doSearch()->paginate($this->per_page);
        return view('admin.maintenance.index', [
            'maintenances' => $maintenances
        ]);
    }

    public function create(Request $request)
    {
        $maintenance = [] ;
        if(Session::has('admin.maintenance.create')) {
            $maintenance = Session::get('admin.maintenance.create');
        }
        return view('admin.maintenance.create', [
            'maintenance' => $maintenance
        ]);
    }

    public function confirm(MaintenanceRequest $request)
    {
        $maintenance = $request->all();
        $maintenance['start_time'] = Carbon::parse("{$request->input('start_year')}-{$request->input('start_month')}-{$request->input('start_day')} {$request->input('start_hour')}:{$request->input('start_minute')}")->format('Y-m-d H:i');
        $maintenance['end_time'] = Carbon::parse("{$request->input('end_year')}-{$request->input('end_month')}-{$request->input('end_day')} {$request->input('end_hour')}:{$request->input('end_minute')}")->format('Y-m-d H:i');
        $maintenance['notice_time'] = Carbon::parse("{$request->input('notice_year')}-{$request->input('notice_month')}-{$request->input('notice_day')} {$request->input('notice_hour')}:{$request->input('notice_minute')}")->format('Y-m-d H:i');

        if(isset($maintenance['id'])) {
            Session::put('admin.maintenance.edit', $maintenance);
        } else {
            Session::put('admin.maintenance.create', $maintenance);
        }
        return view('admin.maintenance.confirm', [
            'maintenance' => $maintenance
        ]);
    }

    public function store(Request $request)
    {
        if(Session::has('admin.maintenance.create')) {
            $maintenance = Session::get('admin.maintenance.create');
            $maintenance['services']  = implode(',', $maintenance['services']);

            if(MaintenanceService::doCreate($maintenance)) {
                Session::forget('admin.maintenance.create');
                $request->session()->flash('success', 'メンテナンスを追加しました。');
            } else {
                $request->session()->flash('error', 'メンテナンス追加に失敗しました。');
            }
            return redirect()->route('admin.maintenance.index');
        } else {
            return redirect()->route('admin.maintenance.create');
        }
    }

    public function edit(Request $request, Maintenance $maintenance)
    {
        if(Session::has('admin.maintenance.edit')) {
            $maintenance = Session::get('admin.maintenance.edit');
        } else {
            $maintenance = $maintenance->toArray();
            $maintenance['services'] = explode(',', $maintenance['services']);
            $maintenance['start_year'] = Carbon::parse($maintenance['start_time'])->format('Y');
            $maintenance['start_month'] = Carbon::parse($maintenance['start_time'])->format('m');
            $maintenance['start_day'] = Carbon::parse($maintenance['start_time'])->format('d');
            $maintenance['start_hour'] = Carbon::parse($maintenance['start_time'])->format('H');
            $maintenance['start_minute'] = Carbon::parse($maintenance['start_time'])->format('i');
            $maintenance['end_year'] = Carbon::parse($maintenance['end_time'])->format('Y');
            $maintenance['end_month'] = Carbon::parse($maintenance['end_time'])->format('m');
            $maintenance['end_day'] = Carbon::parse($maintenance['end_time'])->format('d');
            $maintenance['end_hour'] = Carbon::parse($maintenance['end_time'])->format('H');
            $maintenance['end_minute'] = Carbon::parse($maintenance['end_time'])->format('i');
            $maintenance['notice_year'] = Carbon::parse($maintenance['notice_time'])->format('Y');
            $maintenance['notice_month'] = Carbon::parse($maintenance['notice_time'])->format('m');
            $maintenance['notice_day'] = Carbon::parse($maintenance['notice_time'])->format('d');
            $maintenance['notice_hour'] = Carbon::parse($maintenance['notice_time'])->format('H');
            $maintenance['notice_minute'] = Carbon::parse($maintenance['notice_time'])->format('i');
        }

        return view('admin.maintenance.edit', [
            'maintenance' => $maintenance
        ]);
    }

    public function update(Request $request, Maintenance $maintenance)
    {
        if(Session::has('admin.maintenance.edit')) {
            $arr_maintenance = Session::get('admin.maintenance.edit');
            $arr_maintenance['services']  = implode(',', $arr_maintenance['services']);

            if(MaintenanceService::doUpdate($maintenance, $arr_maintenance)) {
                Session::forget('admin.maintenance.edit');
                $request->session()->flash('success', 'メンテナンスを編集しました。');
            } else {
                $request->session()->flash('error', 'メンテナンス編集に失敗しました。');
            }
            return redirect()->route('admin.maintenance.index');
        } else {
            return redirect()->route('admin.maintenance.edit', ['maintenance'=>$maintenance->id]);
        }
    }

    public function detail(Request $request, Maintenance $maintenance)
    {
        return view('admin.maintenance.detail', [
            'maintenance' => $maintenance
        ]);
    }

    public function delete(Request $request, Maintenance $maintenance)
    {
        if($maintenance->delete()) {
            $request->session()->flash('success', 'メンテナンスを削除しました。');
        } else {
            $request->session()->flash('error', 'メンテナンス削除に失敗しました。');
        }
        return redirect()->route('admin.maintenance.index');
    }

}
