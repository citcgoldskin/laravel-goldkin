<?php

namespace App\Service;

use DB;

class BaseCateService
{
    public $_table = "";
    public $_id = "";
    public $_name = "";
    public $_parent = "";

    public function getList($parent)
    {
        return DB::table($this->_table)
            ->where($this->_parent, $parent)
            ->selectRaw($this->_id . " as id")
            ->get()
            ->toArray();
    }

    //$self : true => contain, false => not contain
    public function getAllChildren($parent, $self = true)
    {
        $result = array();
        if($self)
            $result = [$parent];
        $list = $this->getList($parent);

        if($list)
        {
            foreach($list as $val)
            {
                $result[] = $val->id;
                $result = array_merge($result, $this->getAllChildren($val->id, false));
            }
        }
        return $result;
    }
}
