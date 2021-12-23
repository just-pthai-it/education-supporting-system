<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Term extends Model
{
    public const table = 'term';
    public const table_as = 'term as te';

    use HasFactory;

    protected $table = 'term';
    protected $primaryKey = 'id';
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
