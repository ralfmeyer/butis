<?php

namespace App\Models ;

enum BeurtStatus{
    case none;
    case edit;
    case closed;
    case wait;
}
