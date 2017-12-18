<?php

namespace App;

use Moloquent;

class UserVerification extends Moloquent
{
  /**
   * The collection associated with the model.
   *
   * @var string
   */
  protected $collection = 'user_verify';
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'user_id', 'activationCode'
  ];
}
