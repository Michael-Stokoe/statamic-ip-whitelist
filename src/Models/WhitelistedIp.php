<?php

namespace Stokoe\IpWhitelist\Models;

use Illuminate\Database\Eloquent\Model;
use Statamic\Facades\User;

class WhitelistedIp extends Model
{
    protected $fillable = [
        'ip',
        'name',
        'user_id',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function getAddedByAttribute()
    {
        if (!$this->user_id) {
            return null;
        }

        $user = User::find($this->user_id);
        return $user ? $user->name() ?? $user->email() : 'Unknown User';
    }
}
