<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class MergeController extends Controller
{
  public function status()
  {
    return [ 'message' => 'webhook listening' ];
  }

  public function mergeHook(Request $request)
  {
    try
    {
      // try code
      dispatch(new Tagger($platform));
    }
    catch(\Exception $e){
    }

    return [ 'penis'=> '8====D' ];
  }
}
