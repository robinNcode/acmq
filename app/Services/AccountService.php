<?php
namespace App\Services;

use App\Repositories\Interfaces\AccountRepositoryInterface;

class AccountService
{
    protected $accountRepository;

    public function __construct(AccountRepositoryInterface $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function getAllAccounts()
    {
        return $this->accountRepository->all();
    }

    public function getAccountById($id)
    {
        return $this->accountRepository->find($id);
    }

    public function createAccount(array $data)
    {
        return $this->accountRepository->create($data);
    }

    public function updateAccount($id, array $data)
    {
        return $this->accountRepository->update($id, $data);
    }

    public function deleteAccount($id)
    {
        $account = $this->accountRepository->find($id);
        if ($account->journalEntries()->exists()) {
            return false;
        }
        return $this->accountRepository->delete($id);
    }
}
