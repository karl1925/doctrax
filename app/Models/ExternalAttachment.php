<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalAttachment extends Model
{
    protected $fillable = ['external_id', 'file_path', 'file_name', 'file_type', 'file_size'];
}
