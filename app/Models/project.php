<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
class project extends Model
{
    use HasFactory;

       public $guarded=[];

       protected $casts = [
            'services' => 'array',
            'attachments'=>'array',
            'assignedTo'=>'array'
        ];

     public function customer()
    {
        return $this->belongsTo(customer::class, 'customerId');
    }

    public function getDeadlineAttribute($value): ?string
    {
        if (! $value) {
            return null;
        }

        return Carbon::parse($value)->format('F j, Y');
    }

     public function documents()
    {
        // The default foreign key is "project_id", and local primary key is "id"
        return $this->hasMany(project_document::class);

          
        
    }



      public function payments()
    {
        // The default foreign key is "project_id", and local primary key is "id"
        return $this->hasMany(payment::class);
    }

    public function getTotalPaidAttribute()
{
    return $this->payments()->sum('amount');
}


    public function activities()
    {
        
          return $this->hasMany(activitie::class)->orderBy('created_at', 'desc');
        //   return $this->hasMany(activitie::class)->orderBy('created_at', 'desc')->paginate(10);

          
        
    }

    
}
