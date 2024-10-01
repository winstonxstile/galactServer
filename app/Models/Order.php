<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Определение таблицы, к которой относится модель
    protected $table = 'orders';

    // Определение полей, которые можно заполнять массово
    protected $fillable = ['id','order', 'created_at','updated_at'];
}
