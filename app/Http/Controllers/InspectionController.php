<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Inspection;
use App\Image;
use App\ImageMarker;

class InspectionController extends Controller
{
  protected function validator(array $data)
  {
    return Validator::make($data, [
        'inspectiondate' => 'required',
        // 'achievedate' => 'required'
    ]);
  }

  private function checkOccasion(Request $request){
    if($request['lackadjustment'] || $request['monitoring'] || $request['operatingmeasure'] || $request['special_audit'] || $request['cut_deadline'] || $request['cut_deadline']){
      return true;
    } else {
      return false;
    }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    $validator = $this->validator($request->all());

    if ($validator->fails()) {
      return response()->json($validator->messages(), 200);
    }

    $inspectionItem = Inspection::create([
      'bridge_id' => $request['bridge_id'],
      'inspectiondate' => $request['inspectiondate'],
      'achievedate' => $request['achievedate'],
      'achievedate' => $request['lackadjustment'],
      'monitoring' => $request['monitoring'],
      'operatingmeasure' => $request['operatingmeasure'],
      'special_audit' => $request['special_audit'],
      'cut_deadline' => $request['cut_deadline'],
      'other' => $request['other'],
      'description' => ($this->checkOccasion($request) ? $request['description'] : '')
    ]);

    return response()->json(['success' => 'success', 'inspectionId' => $inspectionItem->id], 200);

    // $portfolio_item->categories()->sync($request['categories']);

  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
    $inspectionItem = Inspection::find($id);
    $imageItems = Image::where('inspection_id', '=', $id)->get();
    return response()->json(['inspectionItem' => $inspectionItem, 'imageItems' => $imageItems], 200);
  }

  /**
   * Display the specified resource for Bridge.
   *
   * @param  int  $id
   * @return Response
   */
  public function showAllforBridge($id)
  {
    $inspectionItems = Inspection::where('bridge_id', '=', $id)->orderBy('inspectiondate', 'desc')->get();
    $inspectionMarkers = ImageMarker::where('bridge_id', '=', $id)->orderBy('created_at', 'desc')->get();
    return response()->json(['inspectionItems' => $inspectionItems, 'inspectionMarkers' => $inspectionMarkers], 200);
    // return response()->json($inspectionItems, 200);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id, Request $request)
  {
    $validator = $this->validator($request->all());

    if ($validator->fails()) {
      return response()->json($validator->messages(), 200);
    }

    $inspectionItem = Inspection::find($id);
    $inspectionItem->inspectiondate = $request['inspectiondate'];
    $inspectionItem->achievedate = $request['achievedate'];
    $inspectionItem->lackadjustment = $request['lackadjustment'];
    $inspectionItem->monitoring = $request['monitoring'];
    $inspectionItem->operatingmeasure = $request['operatingmeasure'];
    $inspectionItem->special_audit = $request['special_audit'];
    $inspectionItem->cut_deadline = $request['cut_deadline'];
    $inspectionItem->other = $request['other'];
    $inspectionItem->description = ($this->checkOccasion($request) ? $request['description'] : '');
    $inspectionItem->save();

    return response()->json(['success' => 'success', 'id' => $inspectionItem->id], 200);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    $imageItems = Image::where('inspection_id', '=', $id)->get();
    foreach ($imageItems as $image) {
      $image_path = public_path('uploads/inspectionimages/').$image->filename;
      unlink($image_path);
      $image->delete();
    }
    $imageMarkers = ImageMarker::where('inspection_id', '=', $id)->get();
    foreach ($imageMarkers as $marker) {
      $marker->delete();
    }
    $inspectionItem = Inspection::where('_id', '=', $id)->first();
    $inspectionItem->delete();
    return response()->json(['success' => 'success']);
  }
}
