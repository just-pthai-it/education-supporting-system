<?php

namespace App\Services;

use Illuminate\Support\Collection;
use App\Http\Resources\AcademicYearResource;
use App\Repositories\Contracts\AcademicYearRepositoryContract;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AcademicYearService implements Contracts\AcademicYearServiceContract
{
    private AcademicYearRepositoryContract $academicYearRepository;

    private const ID_FORMAL_UNIVERSITY = 1;
    private const ID_INFORMAL_UNIVERSITY = 2;
    private const ID_INTER_UNIVERSITY = 3;
    private const MAX_RECENT_ACADEMIC_YEAR_FORMAL = 9;
    private const MAX_RECENT_ACADEMIC_YEAR_INFORMAL = 9;
    private const MAX_RECENT_ACADEMIC_YEAR_INTER = 4;

    /**
     * @param AcademicYearRepositoryContract $academicYearRepository
     */
    public function __construct (AcademicYearRepositoryContract $academicYearRepository)
    {
        $this->academicYearRepository = $academicYearRepository;
    }

    public function readMany (array $inputs) : AnonymousResourceCollection
    {
        if (request()->route('additional') == 'recent')
        {
            $academicYears = $this->__readRecentAcademicYears();
            return AcademicYearResource::collection($academicYears);
        }

        $academicYears = $this->academicYearRepository->find(['id', 'name'], [], [], [],
                                                             [['filter', $inputs]]);
        return AcademicYearResource::collection($academicYears);
    }

    private function __readRecentAcademicYears () : Collection
    {
        $academicYears      = collect([]);
        $optionalParameters = [
            [self::ID_FORMAL_UNIVERSITY, self::MAX_RECENT_ACADEMIC_YEAR_FORMAL],
            [self::ID_INFORMAL_UNIVERSITY, self::MAX_RECENT_ACADEMIC_YEAR_INFORMAL],
            [self::ID_INTER_UNIVERSITY, self::MAX_RECENT_ACADEMIC_YEAR_INTER],
        ];

        foreach ($optionalParameters as $optionalParameter)
        {
            $temp = $this->academicYearRepository->find(['*'],
                                                        [['id_training_type', '=', $optionalParameter[0]]],
                                                        [['id', 'desc']],
                                                        [$optionalParameter[1]]);;
            $academicYears = $academicYears->merge($temp->all());
        }

        return $academicYears;
    }
}
