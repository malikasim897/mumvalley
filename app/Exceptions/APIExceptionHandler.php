<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class APIExceptionHandler extends Exception
{
   
    public function render()
    {
        return back()->withError("Server/API Error:".$this->getMessage());
    }

}
