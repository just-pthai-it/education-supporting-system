<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Term extends Model
{
    use HasFactory, Filterable;

    public const TABLE = 'terms';
    public const TABLE_AS = 'terms as tes';

    protected $table = 'terms';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'id_school_year',
    ];

    public function studySessions () : HasMany
    {
        return $this->hasMany(StudySession::class, 'id_term', 'id');
    }

    public function schoolYear () : BelongsTo
    {
        return $this->belongsTo(SchoolYear::class, 'id_school_year', 'id');
    }
}
