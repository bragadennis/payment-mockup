<?php

namespace App\Enums\Transactions;

use App\Enums\EnumUtilities;

enum Reason:string {
    use EnumUtilities;

    case INSUFICIENT_FUNDS = 'insuficient_funds';
    case UNAUTHORIZED_PAYER_TYPE = 'unauthorized_payer_type';
    case EXTERNAL_AUTHORIZATION_REFUSED = 'external_authorization_refused';
    case UNHANDLED_ERROR = 'unhandled_error';
}
