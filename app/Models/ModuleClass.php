<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModuleClass extends Model
{
    use HasFactory;

    public const table = 'module_class';
    public const table_as = 'module_class as mc';

    protected $table = 'module_class';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'module_class_name',
        'number_plan',
        'number_reality',
        'id_school_year',
        'id_module',
        'id_teacher',
    ];

    protected $hidden = [
        'pivot'
    ];

    public function module () : BelongsTo
    {
        return $this->belongsTo(Module::class, 'id_module', 'id');
    }

    public function teacher () : BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'id_teacher', 'id');
    }

    public function schedules () : HasMany
    {
        return $this->hasMany(Schedule::class, 'id_module_class', 'id');
    }

    public function students () : BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'participate', 'id_module_class', 'id_student');
    }
}
