<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class UserCollection extends BaseCollection
{
   
    public function toArray($request)
    {
        return [
            'data' => UserResource::collection($this->collection),
            'addtional_information' => $this->paginate(),
            'more_informarion' => $this->next_paginate()

        ];
    }
}
