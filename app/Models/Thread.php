<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * 
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /**
     * 
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->whereNull('parent_id');
    }
    /**
     * 
     */
    public function isOlderThanSixHours(): bool
    {
        return strtotime($this->created_at) <= strtotime('-6 hours');
    }
}
