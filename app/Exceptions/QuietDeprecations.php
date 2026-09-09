<?php

namespace App\Exceptions;

use Illuminate\Foundation\Bootstrap\HandleExceptions;

class QuietDeprecations extends HandleExceptions
{
    public function bootstrap($app)
    {
        parent::bootstrap($app);

        error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
    }
}
