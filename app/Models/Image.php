<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    // Определение таблицы, к которой относится модель
    protected $table = 'images';

    // Определение полей, которые можно заполнять массово
    protected $fillable = ['id','name','path','created_at','updated_at'];
}
