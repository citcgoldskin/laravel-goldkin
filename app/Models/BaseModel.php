<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    /**
     * |---------------------------------------------------------------------
     * | @author vungpv
     * | @description flag delete;
     * | DELFLAG_TRUE => is_deleted;
     * | DELFLAG_FALSE => is_active;
     * |---------------------------------------------------------------------
     */

    public function scopeFilter($query, QueryFilter $filters)
    {
        return $filters->apply($query);
    }

    public static function findById($id, $field = null)
    {
        $item = self::where('id', '=', $id)->first();
        if (!$item) {
            return null;
        }
        if (!$field) {
            return $item;
        }
        return @$item->{$field};

    }
}
