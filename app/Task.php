<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //
    protected $table="tasks";

    function assignedTo()
    {
        return $this->belongsTo(People::class, 'reference', 'id');
    }

    function assignedBy()
    {
        return $this->belongsTo(People::class, 'assigned_by', 'id');
    }
}
