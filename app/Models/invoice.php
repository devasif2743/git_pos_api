<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class invoice extends Model
{
    use HasFactory;

      public $guarded=[];

         protected $casts = [
             'services'    => 'array',
      
           ];

      public function customer()
    {
        return $this->belongsTo(customer::class,'customer_id','id');
    }

     public function project()
    {
        return $this->belongsTo(project::class,'project_id','id');
    }


      public function payments()
    {
        return $this->hasMany(payment::class, 'project_id', 'project_id');
    }





}
