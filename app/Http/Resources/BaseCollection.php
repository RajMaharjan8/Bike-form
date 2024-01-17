<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseCollection extends ResourceCollection
{
    public function paginate()
    {
        return [
            'total' => $this->total(),
            'current' => $this->currentPage(),
            'last' => $this->lastPage(),
            'elements_in_each_page' => $this->perPage()
        ];
    }

    public function next_paginate()
    {
        return [
            'this_is_next_page_url' => $this->nextPageUrl()
        ];
    }
}
