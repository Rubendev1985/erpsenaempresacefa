<?php

namespace Modules\Apicola\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Colmena extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'apicola_colmenas';

    protected $fillable = [
        'code',
        'apiario_name',
        'apiario_id',
        'responsable',
        'tipo_abeja',
        'installation_date',
        'last_visit_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'installation_date' => 'date',
        'last_visit_date' => 'date',
    ];

    /**
     * Scopes para filtrado rápido de estados
     */
    public function scopeActivas($query)
    {
        return $query->where('status', 'Activa');
    }

    public function scopeEnRevision($query)
    {
        return $query->where('status', 'En revisión');
    }

    public function scopeInactivas($query)
    {
        return $query->where('status', 'Inactiva');
    }
}
