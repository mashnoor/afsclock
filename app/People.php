<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    //
    protected $table="people";

    function tasks()
    {
        return $this->hasMany(Task::class, 'reference', 'id');
    }
}
