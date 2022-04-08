<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Module extends Model
{
    use HasFactory;

    public const table = 'modules';
    public const table_as = 'modules as mds';

    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'credit',
        'semester',
        'theory',
        'exercise',
        'project',
        'experiment',
        'practice',
        'option',
        'id_department'
    ];

    public function department () : BelongsTo
    {
        return $this->belongsTo(Department::class, 'id_department', 'id');
    }

    public function moduleClasses () : HasMany
    {
        return $this->hasMany(ModuleClass::class, 'id_module', 'id');
    }

    public function curriculums () : BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'curriculum_module', 'id_module',
                                    'id_curriculum');
    }
}
