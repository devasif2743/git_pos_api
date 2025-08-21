<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customer extends Model
{
    use HasFactory;

    public $guarded=[];


     public function projects()
    {
        return $this->hasMany(project::class, 'customerId');
    }

    public function invoices()
        {
            return $this->hasMany(invoice::class);
        }

 public function meetings()
{
    return $this->hasMany(calender::class, 'customer_id')->where('type', 'meeting');
}

 public function meetingss()
{
    return $this->hasMany(calender::class, 'customer_id')->where('type', 'task');
}

}
