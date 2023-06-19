<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "posts";
    protected $fillable = ['title', 'content', 'user_id'];
    public $timestamps = true;

    public function user() {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        );
    }

    public function trashedUser() {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        )->onlyTrashed();
    } 
}
