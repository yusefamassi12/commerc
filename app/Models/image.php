<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class image extends Model
{
    use HasFactory;
    protected $fillable=['src','photoable_type'];
    public function photoable(){
        return $this->morphTo();
    }
}
