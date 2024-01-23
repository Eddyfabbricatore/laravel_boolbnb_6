<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Sponsor;
use App\Models\Message;
use App\Models\View;

class Apartment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function services(){
        return $this->belongsToMany(Service::class);
    }

    public function sponsors()
    {
        return $this->belongsToMany(Sponsor::class)->withPivot('transaction_date');
    }

    public function messages(){
        return $this->hasMany(Message::class);
    }

    public function views(){
        return $this->hasMany(View::class);
    }
}
