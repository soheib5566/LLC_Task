<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'application_id',
        'path',
        'original_name',
    ];
    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
