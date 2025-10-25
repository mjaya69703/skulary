<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Biodata extends Model
{
    protected $table = 'biodatas';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
