<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Alamat extends Model
{
    protected $table = 'alamats';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
