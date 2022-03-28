<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use App\Repositories\Contracts\TagRepositoryContract;
use App\Repositories\Contracts\AccountRepositoryContract;
use App\Repositories\Contracts\NotificationRepositoryContract;

class NotificationService implements Contracts\NotificationServiceContract
{
    private NotificationRepositoryContract $notificationRepository;
    private AccountRepositoryContract $accountRepository;
    private TagRepositoryContract $tagRepository;

    /**
     * @param NotificationRepositoryContract $notificationRepository
     * @param AccountRepositoryContract      $accountRepository
     * @param TagRepositoryContract          $tagRepository
     */
    public function __construct (NotificationRepositoryContract $notificationRepository,
                                 AccountRepositoryContract      $accountRepository,
                                 TagRepositoryContract          $tagRepository)
    {
        $this->notificationRepository = $notificationRepository;
        $this->accountRepository      = $accountRepository;
        $this->tagRepository          = $tagRepository;
    }


    public function store (array $inputs)
    {
        $this->_completePostInputs($inputs);
        $notificationArray = Arr::except($inputs, ['tag_names', 'accountable_ids']);
        $idNotification    = $this->notificationRepository->insertGetId($notificationArray);

        if (!empty($inputs['tag_names']))
        {
            $tags       = $this->_readTagsByNames($inputs['tag_names']);
            $idTags     = $this->_getIdTagsFromTags($tags);
            $idAccounts = $this->_getIdAccountsFromTags($tags);
            $this->_createNotificationTag($idNotification, $idTags);
        }
        else
        {
            $idAccounts = $this->_readIdAccountsByNotifiableIds($inputs['accountable_ids']);
        }

        $this->_createNotificationAccount($idNotification, $idAccounts);
    }

    private function _completePostInputs (&$inputs)
    {
        $inputs['id_account'] = auth()->user()->id;
    }

    public function _readTagsByNames (array $tagNames)
    {
        $conditions = [];

        foreach ($tagNames as $tagName)
        {
            if (!empty($conditions))
            {
                $conditions[] = ['name', '|like', $tagName];
            }
            else
            {
                $conditions[] = ['name', 'like', $tagName];
            }
        }

        return $this->tagRepository->find(['id'], $conditions, [], [], [['with', 'accounts:id']]);
    }

    private function _getIdTagsFromTags (Collection $tags) : array
    {
        return $tags->pluck('id')->toArray();
    }

    private function _getIdAccountsFromTags (Collection $tags) : array
    {
        $idAccounts = [];

        $tags->each(function ($item, $key) use (&$idAccounts)
        {
            $idAccounts = array_merge($idAccounts, $item->accounts->pluck('id')->toArray());
        });

        return $idAccounts;
    }

    private function _readIdAccountsByNotifiableIds (array $notifiableIds)
    {
        return $this->accountRepository->pluck(['id'], [['accountable_id', 'in', $notifiableIds]])
                                       ->toArray();
    }

    private function _createNotificationAccount (string $idNotification, array $idAccounts)
    {
        $this->notificationRepository->insertPivot($idNotification, $idAccounts, 'accounts');
    }

    private function _createNotificationTag (string $idNotification, array $idTags)
    {
        $this->notificationRepository->insertPivot($idNotification, $idTags, 'tags');
    }
}
