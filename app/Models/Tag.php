<?php

namespace App\Models;

class Tag extends BaseModel
{
    protected $fillable = ['name','description','id'];

    public function apis()
    {
        return $this->belongsToMany(Api::class, 'api_tag', 'id_tag', 'id_api');
    }
}
