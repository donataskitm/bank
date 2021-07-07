<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $table = 'accounts';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'balance',
        'main_account',
    ];


    public function transfers(){
        return $this->hasMany(Transfer::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
