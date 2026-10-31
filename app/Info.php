<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Info extends Model
{
    public function details(): HasMany
    {
        return $this->hasMany(InfoDetail::class, 'info_id');
    }
}
