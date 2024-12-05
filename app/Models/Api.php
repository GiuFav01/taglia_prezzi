<?php

namespace App\Models;

class Api extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url',
        'description',
        'last_execution',
        'id'
    ];

    /**
     * Cast attributes to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'last_execution' => 'datetime',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'api_tag', 'id_api', 'id_tag');
    }
}
