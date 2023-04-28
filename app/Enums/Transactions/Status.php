<?php

namespace App\Enums\Transactions;

use App\Enums\EnumUtilities;

enum Status:string {
    use EnumUtilities;

    case APPROVED = 'approved';
    case ATTEMPED = 'attemped';
    case REJECTED = 'rejected';
}
