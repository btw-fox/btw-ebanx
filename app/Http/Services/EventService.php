<?php

namespace App\Http\Services;

use App\Http\DTA\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class EventService {


    public function balance(int $accountId): array
    {
        $account = new Account();
        $foundAccount = $account->find($accountId);
        return $foundAccount;
    }

    public function deposit(int $destination, int $amount): array
    {
        $account = new Account();
        $foundAccount = $account->createOrDeposit($destination, $amount);
        $arrayResonse['destination'] = array_values($foundAccount)[0];
        return $arrayResonse;
    }



    public function withdraw(int $origin, int $amount): array
    {
        $account = new Account();
        $searchAccount = $account->find($origin);
        if($searchAccount['exists'] === true) {
            $account->decreaseBalance(array_values($searchAccount['account'])[0]['id'], $amount);
            $decreasedAcccount = $account->find($origin);
            $arrayResonse['origin'] = array_values($decreasedAcccount['account'])[0];
            return $arrayResonse;
        } else {
            $arrayResonse['exists'] = false;
            return $arrayResonse;
        }
    }

    public function transfer(int $origin, float $amount, int $destination): array
    {
        $account = new Account();
        $originAccount = $account->find($origin);
        $destinationAccount = $account->find($destination);
        if($originAccount['exists'] === true && $destinationAccount['exists'] === true) {
            $account->decreaseBalance(array_values($originAccount['account'])[0]['id'], $amount);
            $decreasedAcccount = $account->find($origin);
            $arrayResonse['origin'] = array_values($decreasedAcccount['account'])[0];

            $account->increaseBalance(array_values($destinationAccount['account'])[0], $amount);
            $increasedAcccount = $account->find($destination);
            $arrayResonse['destination'] = $increasedAcccount['account'];
            return $arrayResonse;
        }else if($originAccount['exists'] === true && $destinationAccount['exists'] === false) {
            $account->decreaseBalance(array_values($originAccount['account'])[0]['id'], $amount);
            $decreasedAcccount = $account->find($origin);
            $arrayResonse['origin'] = array_values($decreasedAcccount['account'])[0];

            $foundAccount = $account->createOrDeposit($destination, $amount);
            $arrayResonse['destination'] = array_values($foundAccount)[0];
            return $arrayResonse;
        } else {
            $arrayResonse['exists'] = false;
            return $arrayResonse;
        }
    }



}
