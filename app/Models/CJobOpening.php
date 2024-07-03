<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CJobOpening extends Model
{
    public function employer()
    {
        return $this->belongsTo(CEmployer::class, 'employerid');
    }
}
