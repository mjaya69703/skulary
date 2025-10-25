<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $guarded = [];


    public function biodata()
    {
        return $this->hasOne(User\Biodata::class);
    }

    public function namaLengkap()
    {
        if ($this->biodata) {
            return trim($this->biodata->nama_depan . ' ' . $this->biodata->nama_belakang);
        }
        return $this->name;
    }

    public function alamat()
    {
        return $this->hasMany(User\Alamat::class);
    }

    public function alamatDomisili()
    {
        return $this->alamat()->where('jenis_alamat', 'Domisili')->first();
    }

    public function alamatKTP()
    {
        return $this->alamat()->where('jenis_alamat', 'KTP')->first();
    }
}
