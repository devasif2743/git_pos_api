<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class expense extends Model
{
    use HasFactory;

      public $guarded=[];


        protected $appends = ['url'];





    public function getUrlAttribute()
{
    return $this->bill_file
        ? asset('public/storage/' . $this->bill_file)
        : null;
}

}
