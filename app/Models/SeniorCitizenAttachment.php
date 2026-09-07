<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeniorCitizenAttachment extends Model
{
    protected $fillable = ['senior_citizen_id', 'file_path', 'file_name'];
}
