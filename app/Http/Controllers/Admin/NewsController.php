<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\NewsPublishRequest;
use App\Http\Requests\Admin\NewsRequest;
use App\Models\News;
use App\Service\NewsService;
use Illuminate\Http\Request;
use Session;
use Storage;
use DB;
use Auth;
use Validator;
use Carbon;

class NewsController extends AdminController
{
    public function index(Request $request)
    {
        return view('admin.news.index');
    }

    public function newsList(Request $request)
    {
        $search_params = $request->input('search_params', []);
        $sort = $request->input('sort', 'new');
        $records = NewsService::doSearch($search_params, $sort)->paginate($this->per_page);
        return view('admin.news.list', [
            'records' => $records,
            'search_params' =>$search_params,
            'sort' => $sort
        ]);
    }

    public function drafts(Request $request)
    {
        $records = NewsService::doSearchDrafts()->paginate($this->per_page);
        return view('admin.news.drafts', [
            'records' => $records,
        ]);
    }

    public function create(Request $request)
    {
        $record = [] ;
        if(Session::has('admin.news.create.info')) {
            $record = Session::get('admin.news.create.info');
        }
        return view('admin.news.create', [
            'record' => $record
        ]);
    }

    public function setInfo(NewsRequest $request, News $news=null)
    {
        $record = $request->all();

        if($request->has('draft')) {
            if(NewsService::doCreate($record)) {
                Session::forget('admin.news.create.info');
                $request->session()->flash('success', '下書きを保存しました。');
            } else {
                $request->session()->flash('error', '下書き保存に失敗しました。');
            }
            return redirect()->route('admin.news.news_list');
        } else {
            if(is_object($news)) {
                Session::put('admin.news.edit.info.'.$news->id, $record);
                return redirect()->route('admin.news.to_publish', ['news'=>$news->id]);
            } else {
                Session::put('admin.news.create.info', $record);
                return redirect()->route('admin.news.to_publish');
            }
        }
    }

    public function toPublish(Request $request, News $news=null)
    {
        if(is_object($news) ) {
            $record = Session::get('admin.news.edit.publish.'.$news->id);
        } else {
            $record = Session::get('admin.news.create.publish');
        }
        return view('admin.news.to_publish', [
            'obj_news' => $news,
            'record' => $record
        ]);
    }

    public function toPrivate(Request $request, News $news)
    {
        return view('admin.news.to_private', [
            'record' => $news
        ]);
    }

    public function store(NewsPublishRequest $request)
    {
        $publish_data = $request->all();
        if(isset($publish_data['id'])) {

        } else {
            Session::put('admin.news.create.publish', $publish_data);
            if(Session::has('admin.news.create.info') && Session::has('admin.news.create.publish')){
                $info = Session::get('admin.news.create.info');
                $publish = Session::get('admin.news.create.publish');
                $info['status'] = $publish['status'] ;
                $info['publish_time'] = $publish['status'] == config('const.news_status_code.publish') ? Carbon::now()->format('Y-m-d H:i') : Carbon::parse("{$request->input('publish_year')}-{$request->input('publish_month')}-{$request->input('publish_day')} {$request->input('publish_hour')}:{$request->input('publish_minute')}")->format('Y-m-d H:i');

                if(NewsService::doCreate($info)) {
                    Session::forget('admin.news.create');
                    $request->session()->flash('success', 'ニュースを追加しました。');
                } else {
                    $request->session()->flash('error', 'ニュース追加に失敗しました。');
                }
                return redirect()->route('admin.news.news_list');
            } else {
                return redirect()->route('admin.news.create');
            }
        }
    }

    public function edit(Request $request, News $news)
    {
        if(Session::has('admin.news.edit.'.$news->id)) {
            $record = Session::get('admin.news.edit.'.$news->id);
        } else {
            $record = $news->toArray();
        }

        return view('admin.news.edit', [
            'record' => $record
        ]);
    }

    public function update(Request $request, News $news)
    {
        $status_type = $request->input('status_type');
        $info = [];
        $info['status'] = $request->input('status') ;
        if($status_type == 'private') {
            $info['dis_publish_time'] = $info['status'] == config('const.news_status_code.n_publish') ? Carbon::now()->format('Y-m-d H:i') : Carbon::parse("{$request->input('n_publish_year')}-{$request->input('n_publish_month')}-{$request->input('n_publish_day')} {$request->input('n_publish_hour')}:{$request->input('n_publish_minute')}")->format('Y-m-d H:i');
            $info['publish_time'] = null;
            if(NewsService::doUpdate($news, $info)) {
                Session::forget('admin.news.edit.' . $news->id);
                $request->session()->flash('success', '非公開設定が完了しました。');
            } else {
                $request->session()->flash('error', '非公開設定に失敗しました。');
            }
            return redirect()->route('admin.news.news_list');
        } else if($status_type == 'publish') {
            $info['publish_time'] = $info['status'] == config('const.news_status_code.publish') ? Carbon::now()->format('Y-m-d H:i') : Carbon::parse("{$request->input('publish_year')}-{$request->input('publish_month')}-{$request->input('publish_day')} {$request->input('publish_hour')}:{$request->input('publish_minute')}")->format('Y-m-d H:i');
            $info['dis_publish_time'] = null;
            if(NewsService::doUpdate($news, $info)) {
                Session::forget('admin.news.edit.' . $news->id);
                $request->session()->flash('success', '差し替え公開設定が完了しました。');
            } else {
                $request->session()->flash('error', '差し替え公開設定に失敗しました。');
            }
            return redirect()->route('admin.news.news_list');
        } else {
            return redirect()->route('admin.news.edit', ['news'=>$news->id]);
        }
    }

    public function detail(Request $request, News $news)
    {
        return view('admin.news.detail', [
            'record' => $news
        ]);
    }

    public function delete(Request $request)
    {
        $ids = $request->input('ids');
        if(is_array($ids) && NewsService::doDelete($ids)) {
            $request->session()->flash('success', 'ニュースを削除しました。');
        } else {
            $request->session()->flash('error', 'ニュース削除に失敗しました。');
        }
        return redirect()->route('admin.news.drafts');
    }

}
