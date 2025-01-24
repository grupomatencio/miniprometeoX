<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'oauth_clients';

    protected $fillable = [
        'id',
        'user_id',
        'name',
        'secret',
        'personal_access_client',
        'password_client',
        'revoked',
    ];

    public $timestamps = true; // Si la tabla tiene created_at y updated_at

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

