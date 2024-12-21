<?php

namespace App\Helpers;

use Illuminate\Pagination\LengthAwarePaginator;

class Helpers
{
    /**
     * Paginate a collection or array.
     *
     * @param  array|\Illuminate\Support\Collection  $items
     * @param  int  $perPage
     * @param  string|null  $baseUrl
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public static function paginate($data)
    {

        return [
            'data' => $data->items(),
            'total' => $data->total(),
            'per_page' => $data->perPage(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
        ];
    }

    public static function mappingAttribute($attribute, $list) {
        return isset($list[$attribute]) ? $list[$attribute] : "unknown";
    }
}
