<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Curriculum extends Model
{
    use HasFactory;

    public const TABLE = 'curriculums';
    public const TABLE_AS = 'curriculums as ccs';

    protected $table = 'curriculums';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
    ];

    public function modules () : BelongsToMany
    {
        return $this->belongsToMany(Module::class, 'curriculum_module', 'id_curriculum',
                                    'id_module');
    }
}
