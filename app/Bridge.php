<?php

namespace App;

use Moloquent;

class Bridge extends Moloquent
{
  /**
   * The collection associated with the model.
   *
   * @var string
   */
  protected $collection = 'bridges';
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'route', 'point', 'component'
  ];

  public function inspections()
  {
    return $this->hasMany('App\Inspection');
  }
}
