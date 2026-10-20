<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'edition_id', 'page_number', 'thumb_path', 'normal_path', 'hires_path'
    ];

    public function edition()
    {
        return $this->belongsTo(Edition::class);
    }
}
