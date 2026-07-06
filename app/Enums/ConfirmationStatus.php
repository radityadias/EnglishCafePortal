<?php

namespace App\Enums;

enum ConfirmationStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
    //
}
