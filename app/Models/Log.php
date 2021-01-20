<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    public function getLogsAttribute($value)
    {
        return unserialize($value);
    }

    public function setLogsAttribute($value)
    {
        $this->attributes['logs']  = serialize($value);
    }
}
