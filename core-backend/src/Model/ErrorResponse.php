<?php

namespace App\Model;

class ErrorResponse
{
  public string $message;
  public int $code;

  public function __construct(string $message, int $code)
  {
    $this->message = $message;
    $this->code = $code;
  }
}
