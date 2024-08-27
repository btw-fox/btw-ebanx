<?php

namespace App\Http\DTA;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class Account {

    public array $accounts;

    // public function __construct(int $id = null, int $amount = null)
    // {
    //     Log::info('----- construct -----');
    //     if($id !== null && $amount !== null) {
    //         Log::info("@@ search and create $id");
    //         $account = $this->find($id);
    //         if($account['account'] === false) {

    //         } else {
    //             Log::info('@@ existing account');
    //             return $account;
    //         }
    //     }

    // }

    public function reset(): bool
    {
        Log::info('----- reset state -----');
        Log::info(Cache::get('accounts'));
        Cache::put('accounts', []);
        Log::info(Cache::get('accounts'));
        return true;
    }


    public function createOrDeposit(int $id, float $amount): array
    {
        $account = $this->find($id);

        if($account['account'] == false) {
            $newAccount = ['id' => "$id", 'balance' => $amount];
            $this->accounts = Cache::get('accounts');
            array_push($this->accounts, $newAccount);
            Cache::put('accounts', $this->accounts);
            $newAccount = $this->find($id);
            return $newAccount['account'];
        } else {
            $this->increaseBalance(array_values($account['account'])[0], $amount);
            $increasedAccount = $this->find($id);
            return $increasedAccount['account'];
        }
    }


    public function find(int $id): array
    {
        $this->accounts = Cache::get('accounts');

        $foundAccount = array_filter($this->accounts, function($account) use ($id){
            return $account['id'] == $id;
        });

        if($foundAccount) {
            $account = ['exists' => true, 'account' => $foundAccount];
            return $account ;
        } else {
            return ['exists' => false, 'account' => false];
        }

    }

    public function increaseBalance(array $account, int $amount): void
    {
        $this->accounts = Cache::get('accounts');
        foreach ($this->accounts as $key => $searchAccount) {
            if ($searchAccount['id'] === $account['id']) {
                $oldBalance = $this->accounts[$key]['balance'];
                unset($this->accounts[$key]);
                break;
            }
        }
        $recreatedAccount = ['id' => "{$account['id']}", 'balance' => $oldBalance + $amount];
        array_push($this->accounts, $recreatedAccount);
        Cache::put('accounts', $this->accounts);
    }

    public function decreaseBalance(int $origin, int $amount): void
    {
        $this->accounts = Cache::get('accounts');
        $account = $this->find($origin);
        $oldBalance = 0;
        // dd($account);
        foreach ($this->accounts as $key => $searchAccount) {
            if ($searchAccount['id'] == $origin) {
                $oldBalance = $this->accounts[$key]['balance'];
                unset($this->accounts[$key]);
                break;
            }
        }
        $recreatedAccount = ['id' => "$origin", 'balance' => $oldBalance - $amount];
        array_push($this->accounts, $recreatedAccount);
        Cache::put('accounts', $this->accounts);
    }

}
