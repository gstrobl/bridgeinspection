<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Image;
use App\ImageMarker;
use App\Inspection;

class ImageMarkerController extends Controller
{
  protected function validator(array $data)
  {
    return Validator::make($data, [
        'damage_description' => 'required',
        'classification' => 'required'
    ]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store($imageId, $inspectionId, Request $request)
  {
    $validator = $this->validator($request->all());

    if ($validator->fails()) {
      return response()->json($validator->messages(), 200);
    }

    $imageMarkerItem = ImageMarker::create([
      'inspection_id' => $inspectionId,
      'image_id' => $imageId,
      'damage_description' => $request['damage_description'],
      'classification' => $request['classification']
    ]);

    return response()->json(['success' => 'success', 'imageMarkerItem' => $imageMarkerItem->id, 200]);

  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function showMarkers($imageId, $inspectionId)
  {
    $imageMarkerItems = ImageMarker::where('inspection_id', '=', $inspectionId)->where('image_id', '=', $imageId)->orderBy('created_at', 'desc')->get();
    return response()->json($imageMarkerItems, 200);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function showMarker($id)
  {
    $imageMarkerItem = ImageMarker::find($id);
    return response()->json($imageMarkerItem, 200);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($imageId, $inspectionId, $markerId, Request $request)
  {
    $validator = $this->validator($request->all());

    if ($validator->fails()) {
      return response()->json($validator->messages(), 200);
    }

    $imageMarkerItem = ImageMarker::find($markerId);
    $imageMarkerItem->damage_description = $request['damage_description'];
    $imageMarkerItem->classification = $request['classification'];
    $imageMarkerItem->save();

    return response()->json(['success' => 'success', 'id' => $imageMarkerItem->id], 200);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function delete($id)
  {
    $imageMarkerItem = ImageMarker::where('_id', '=', $id)->first();
    $imageMarkerItem->delete();
    return response()->json(['success' => 'success']);
  }
}
