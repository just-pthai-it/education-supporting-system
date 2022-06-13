<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudySession extends Model
{
    use HasFactory, Filterable;

    public const TABLE = 'study_sessions';
    public const TABLE_AS = 'study_sessions as sss';

    protected $table = 'study_sessions';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'id_term',
    ];

    private array $filterable = [];

    private array $sortable = [
        'id',
    ];

    public function term () : BelongsTo
    {
        return $this->belongsTo(Term::class, 'id_term', 'id');
    }

    public function moduleClasses () : HasMany
    {
        return $this->hasMany(ModuleClass::class, 'id_study_session', 'id');
    }
}
