<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Group extends Model
{
    use HasFactory;

    protected $table = "groups";
    protected $fillable = ['name', 'permissions', 'user_id'];
    public $timestamps = true;

    public function users() {
        return $this->hasMany(
            User::class,
            'group_id',
            'id'
        );
    }

    public function user() {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        );
    }
}
