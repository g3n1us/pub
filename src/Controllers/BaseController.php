<?php
namespace G3n1us\Pub\Controllers;

use App\Http\Controllers\Controller as BaseLaravelAppController;
use Illuminate\Http\Request;
use Auth;
use View;
use Brand;

/**
 * BaseController other controllers extend from this.
 */
class BaseController extends BaseLaravelAppController {		
    public function __construct(Request $request){
		$this->loggedin = Auth::check();
		$this->brand = Brand::first();
// 		parent::__construct();
    }
}


