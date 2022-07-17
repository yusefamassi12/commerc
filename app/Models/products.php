<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\models\image;


class products extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'regularprice','categoryid'
    ];
    public function images(){

        return $this->morphMany(image::class,'photoable');
    }

}
