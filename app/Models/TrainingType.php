<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingType extends Model
{
    use HasFactory, Filterable;

    public const table = 'training_types';
    public const table_as = 'training_types as tts';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
    ];

    public function academicYears () : HasMany
    {
        return $this->hasMany(AcademicYear::class, 'id_training_type', 'id');
    }

}
