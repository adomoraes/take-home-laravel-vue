<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pacote extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser preenchidos em massa (Mass Assignable).
     *
     * ESTA É A CORREÇÃO: Precisamos de listar 'name' e 'observations'
     * aqui para permitir que o Pacote::create() funcione.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'observations',
    ];

    /**
     * Define o relacionamento "Muitos-para-Muitos" com Exame.
     * Um pacote pode conter múltiplos exames.
     */
    public function exames(): BelongsToMany
    {
        return $this->belongsToMany(Exame::class, 'exame_pacote');
    }
}