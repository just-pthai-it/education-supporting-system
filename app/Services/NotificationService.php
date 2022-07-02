<?php

namespace App\Services;

use App\Models\Teacher;
use App\Models\Student;
use App\Helpers\Constants;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\TagRepositoryContract;
use App\Repositories\Contracts\ClassRepositoryContract;
use App\Repositories\Contracts\AccountRepositoryContract;
use App\Repositories\Contracts\StudentRepositoryContract;
use App\Repositories\Contracts\ModuleClassRepositoryContract;
use App\Repositories\Contracts\NotificationRepositoryContract;

class NotificationService implements Contracts\NotificationServiceContract
{
    private NotificationRepositoryContract $notificationRepository;
    private ModuleClassRepositoryContract $moduleClassRepository;
    private AccountRepositoryContract $accountRepository;
    private StudentRepositoryContract $studentRepository;
    private ClassRepositoryContract $classRepository;
    private TagRepositoryContract $tagRepository;

    /**
     * @param NotificationRepositoryContract $notificationRepository
     * @param AccountRepositoryContract      $accountRepository
     * @param TagRepositoryContract          $tagRepository
     * @param ClassRepositoryContract        $classRepository
     * @param StudentRepositoryContract      $studentRepository
     * @param ModuleClassRepositoryContract  $moduleClassRepository
     */
    public function __construct (NotificationRepositoryContract $notificationRepository,
                                 AccountRepositoryContract      $accountRepository,
                                 TagRepositoryContract          $tagRepository,
                                 ClassRepositoryContract        $classRepository,
                                 StudentRepositoryContract      $studentRepository,
                                 ModuleClassRepositoryContract  $moduleClassRepository)
    {
        $this->notificationRepository = $notificationRepository;
        $this->accountRepository      = $accountRepository;
        $this->tagRepository          = $tagRepository;
        $this->classRepository        = $classRepository;
        $this->studentRepository      = $studentRepository;
        $this->moduleClassRepository  = $moduleClassRepository;
    }

    public function store (array $inputs)
    {
        $notificationArray = Arr::only($inputs, ['data']);
        $idAccounts        = [];
        $idTags            = [];

        $this->__getIdAccountsAndIdTags($inputs, $idAccounts, $idTags);
        DB::transaction(function () use ($notificationArray, $idAccounts, $idTags)
        {
            $idNotification = $this->__createNotification($notificationArray);
            $this->__createManyNotificationAccount($idNotification, $idAccounts);
            $this->__createManyNotificationTag($idNotification, $idTags);
        }, 2);

        return response('', 201);
    }

    private function __getIdAccountsAndIdTags (array $inputs, array &$idAccounts, array &$idTags)
    {
        switch (request()->route('option'))
        {
            case Constants::FOR_TEACHERS_IN_FACULTIES:
            case Constants::FOR_TEACHERS_IN_DEPARTMENTS:
                $taggableIds = array_merge($inputs['taggable_ids']['faculties'] ?? [],
                                           $inputs['taggable_ids']['departments'] ?? []);
                $tags        = $this->__readTagsByTaggableIds($taggableIds, true, true);
                $idAccounts  = $this->__getIdAccountsByTags($tags);
                $idTags      = $tags->pluck('id')->all();
                break;

            case Constants::FOR_STUDENTS_IN_FACULTIES_AND_ACADEMIC_YEARS:
                $idClasses   = $this->__readIdClassesByIdAcademicYearAndIdFacultyPairs($inputs['taggable_ids']['academic_years'],
                                                                                       $inputs['taggable_ids']['faculties']);
                $taggableIds = array_merge($inputs['taggable_ids']['academic_years'],
                                           $inputs['taggable_ids']['faculties']);
                $idAccounts  = $this->__readIdAccountsByStudentsByIdClasses($idClasses);
                $idTags      = $this->__readTagsByTaggableIds($taggableIds)->pluck('id')->all();
                break;

            case Constants::FOR_STUDENTS_IN_MODULE_CLASSES;
                $idAccounts = $this->__readIdAccountsByStudentsByIdModuleClasses($inputs['taggable_ids']['module_classes']);
                $idTags     = $this->__readTagsByTaggableIds($inputs['taggable_ids']['module_classes'])
                                   ->pluck('id')->all();
                break;

            case Constants::FOR_STUDENTS_BY_IDS:
                $idAccounts = $this->__readIdAccountsByIdStudents($inputs['id_students']);
                break;
        }
    }

    private function __readIdClassesByIdAcademicYearAndIdFacultyPairs (array $idAcademicYears,
                                                                       array $idFaculties) : array
    {
        return $this->classRepository->findIdClassesByIdAcademicYearAndIdFacultyPairs($idAcademicYears,
                                                                                      $idFaculties)
                                     ->all();
    }

    private function __readIdAccountsByStudentsByIdClasses (array $idClasses) : array
    {
        return $this->studentRepository->find(['id'], [['id_class', 'in', $idClasses]], [], [],
                                              [['with', 'account:id,accountable_type,accountable_id']])
                                       ->pluck('account.id')->filter()->all();
    }

    private function __readTagsByTaggableIds (array $taggableIds,
                                              bool  $isWithAccounts = false,
                                              bool  $isForTeacher = false) : Collection
    {
        $targetAudience = $isForTeacher ? Teacher::class : Student::class;
        return $this->tagRepository->find(['id'],
                                          [['taggable_id', 'in', $taggableIds],
                                           ['target_audience', '=', $targetAudience]],
                                          [], [],
                                          $isWithAccounts ? [['with', 'accounts:id']] : []);
    }

    private function __readIdAccountsByStudentsByIdModuleClasses (array $idModuleClasses) : array
    {
        $moduleClasses = $this->moduleClassRepository->find(['id'],
                                                            [['id', 'in', $idModuleClasses]],
                                                            [], [],
                                                            [['with', 'students:id', 'students.account:id,accountable_id']]);

        $idAccounts = $moduleClasses->map(function ($item, $key)
        {
            return $item->students->pluck('account.id');
        });

        return $idAccounts->collapse()->filter()->all();
    }

    private function __readIdAccountsByIdStudents (array $idStudents) : array
    {
        return $this->accountRepository->pluck(['id'], [['accountable_id', 'in', $idStudents]])
                                       ->all();
    }

    private function __createNotification (array $values) : int
    {
        $values['type'] = Constants::NOTIFICATION_TYPE[request()->route('option')];
        return auth()->user()->notification()->create($values)->id;
    }

    private function __getIdAccountsByTags (Collection $tags) : array
    {
        $idAccounts = $tags->map(function ($item, $key)
        {
            return $item->accounts->pluck('id');
        });

        return $idAccounts->collapse()->all();
    }

    private function __createManyNotificationAccount (string $idNotification, array $idAccounts)
    {
        if (!empty($idAccounts))
        {
            $this->notificationRepository->insertPivot($idNotification, $idAccounts, 'accounts');
        }
    }

    private function __createManyNotificationTag (string $idNotification, array $idTags)
    {
        if (!empty($idTags))
        {
            $this->notificationRepository->insertPivot($idNotification, $idTags, 'tags');
        }
    }
}
