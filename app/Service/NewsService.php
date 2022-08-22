<?php

namespace App\Service;

use App\Models\Maintenance;
use App\Models\News;
use Auth;
use DB;
use Storage;

class NewsService
{
    public static function doSearch($search_params=[], $sort='new') {

        if($sort == 'new') {
            $d = 'desc';
        } else {
            $d= 'asc';
        }
        $records = News::orderBy('created_at', $d);
           /* ->where(function ($query) {
                $query->where('status',  config('const.news_status_code.publish'))
                    ->orWhere('status', config('const.news_status_code.limit_publish'));
            });*/

        if(isset($search_params['keyword']) && $search_params['keyword']) {
            $records->where(function ($query) use($search_params) {
                $query->where('title', 'like', "%{$search_params['keyword']}%")
                    ->orWhere('content', 'like', "%{$search_params['keyword']}%");
            });
        }
        return $records;
    }

    public static function doSearchDrafts($search_params=[], $sort='new') {

        return News::orderBy('created_at', 'desc')
            ->where(function ($query) {
             $query->where('status',  config('const.news_status_code.draft'))
                 ->orWhere('status', config('const.news_status_code.n_publish'))
                 ->orWhere(function ($q){
                    $q->where('status', config('const.news_status_code.limit_n_publish'))
                     ->where('dis_publish_time', '>=', 'NOW()');
                 });
            });
    }

    public static function doCreate($data)
    {
        return News::create($data);
    }

    public static function doUpdate(News $obj_news,  $data)
    {
        if($obj_news) {
            return $obj_news->update($data);
        }
        return false;
    }

    public static function doDelete($ids=[])
    {
        return News::whereIn('id', $ids)
            ->delete();
    }


}
