<?php

namespace App\Traits\User;

use App\Models\Admin\Currency;
use App\Models\UserWallet;
use Exception;

trait RegisteredUsers {
   
    protected function breakAuthentication($error) {
        return back()->with(['error' => [$error]]);
    }
}
