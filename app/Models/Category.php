<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'created_by'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function admins()
    {
        return $this->belongsToMany(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeForAdmin($query, $userId)
    {
        return $query->whereHas('admins', function ($q) use ($userId) {
            $q->where('users.id', $userId);
        });
    }
}
