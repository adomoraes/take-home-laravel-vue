<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pacote extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'observations',
    ];

    public function exames(): BelongsToMany
    {
        return $this->belongsToMany(Exame::class, 'exame_pacote');
    }
}