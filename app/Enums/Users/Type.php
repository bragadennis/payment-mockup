<?php

namespace App\Enums\Users;

use App\Enums\EnumUtilities;

enum Type:string {
    use EnumUtilities;

    case CUSTOMER = 'customer';
    case SELLER = 'seller';
}
