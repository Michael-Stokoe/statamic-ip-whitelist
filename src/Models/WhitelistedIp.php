<?php

namespace Stokoe\IpWhitelist\Models;

use Illuminate\Database\Eloquent\Model;

class WhitelistedIp extends Model
{
    protected $fillable = [
        'ip',
        'name',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
