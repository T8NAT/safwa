<?php

namespace App\Constants;

class GlobalConst {
    const USER_PASS_RESEND_TIME_MINUTE = "1";

    const ACTIVE = true;
    const BANNED = false;
    const DEFAULT_TOKEN_EXP_SEC = 3600;

    const VERIFIED = 1;
    const APPROVED = 1;
    const PENDING = 2;
    const REJECTED = 3;
    const DEFAULT = 0;
    const UNVERIFIED = 0;
    const BOOKED = 1;
    const ONGOING = 2;
    const COMPLETED = 3;
    const SETUP_PAGE = 'SETUP_PAGE';
    const SYSTEM_MAINTENANCE = "system-maintenance";
    const USEFUL_LINKS = 'USEFUL_LINKS';
}
