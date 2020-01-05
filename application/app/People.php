<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    //
    protected $table="tbl_people";

    function tasks()
    {
        return $this->hasMany(Task::class, 'reference', 'id');
    }
}
