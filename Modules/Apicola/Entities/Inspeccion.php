<?php

namespace Modules\Apicola\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inspeccion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'apicola_inspecciones';

    protected $fillable = [
        'registration_number',
        'colmena_code',
        'colmena_id',
        'date',
        'responsable',
        'general_state',
        'honey_presence',
        'harvested_honey_kg',
        'queen_seen',
        'queen_marked',
        'queen_color',
        'diseases',
        'tasks',
        'feed_supplied',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'harvested_honey_kg' => 'decimal:2',
        'queen_seen' => 'boolean',
        'queen_marked' => 'boolean',
        'diseases' => 'array',
        'tasks' => 'array',
    ];

    /**
     * Relación con Colmena
     */
    public function colmena()
    {
        return $this->belongsTo(Colmena::class, 'colmena_id');
    }
}
