<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    use HasFactory;

    public const TABLE = 'students';
    public const TABLE_AS = 'students as stus';

    protected $table = 'students';
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'is_female',
        'birth',
        'address',
        'id_class',
    ];

    public function account () : MorphOne
    {
        return $this->morphOne(Account::class, 'accountable');
    }

    public function class_ () : BelongsTo
    {
        return $this->belongsTo(Class_::class, 'id_class', 'id');
    }

    public function moduleClasses () : BelongsToMany
    {
        return $this->belongsToMany(ModuleClass::class, 'module_class_student',
                                    'id_student', 'id_module_class');
    }

    public function dataVersionStudent () : HasOne
    {
        return $this->hasOne(DataVersionStudent::class, 'id_student', 'id');
    }
}
