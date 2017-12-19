<?php

namespace App;

use Moloquent;

class Inspection extends Moloquent
{
  /**
   * The collection associated with the model.
   *
   * @var string
   */
  protected $collection = 'inspections';
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'bridge_id', 'result', 'description', 'inspectiondate', 'achievedate', 'updated_user_id', 'created_user_id', 'lackadjustment', 'monitoring', 'operatingmeasure', 'special_audit', 'cut_deadline', 'other'
  ];

  public function image()
  {
    return $this->hasMany('App\Image');
  }

  public function imageMarkers()
  {
    return $this->hasMany('App\ImageMarker');
  }
}
