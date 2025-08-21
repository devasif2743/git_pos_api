<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payment extends Model
{
    use HasFactory;

  
 

     protected $fillable = [
        'project_id',
        'payment_date',
        'payment_mode',
        'payment_proof',
        'payment_file',
        'updated_by',
        'amount',
    ];

   protected $appends = ['url'];

    // public function getUrlAttribute()
    // {
      

    //           return env('APP_URL') . $this->payment_file;
        
       
    // }



    public function getUrlAttribute()
{
    return $this->payment_file
        ? asset('storage/' . $this->payment_file)
        : null;
}

}
