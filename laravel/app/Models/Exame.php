<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Exame extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'laterality',
        'comment',
        'group',
    ];

    public function pacotes(): BelongsToMany
    {
        return $this->belongsToMany(Pacote::class, 'exame_pacote');
    }
}