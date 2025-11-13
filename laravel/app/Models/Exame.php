<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Exame extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser preenchidos em massa (Mass Assignable).
     *
     * ESTA É A CORREÇÃO: Precisamos de listar 'name' e os outros
     * campos aqui para permitir que o Exame::create() funcione.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'laterality',
        'comment',
        'group',
    ];

    /**
     * Define o relacionamento "Muitos-para-Muitos" com Pacote.
     * Um exame pode pertencer a múltiplos pacotes.
     */
    public function pacotes(): BelongsToMany
    {
        return $this->belongsToMany(Pacote::class, 'exame_pacote');
    }
}