<?php
namespace G3n1us\Pub\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Storage;
use Auth;
use User;
use SmartyView;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request, \App\Brand $brand){
	    parent::__construct($request, $brand);
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
/*
    public function index(Request $request, $domain)
    {
	    $data['brand'] = \App\Brand::where('handle', BRAND_HANDLE)->first();
	    $data['brands'] = \App\Brand::listable()->get();
	    $data['user'] = Auth::user();
		$data['articles'] = [];
		return response(SmartyView::fetch('dashboard.tpl', $data));
		
//         return view('dashboard', $data);
    }
*/
    

    public function upload(Request $request, $domain, $showresponse = true, $article_id = null){
		if(!$article_id) $article_id = $request->article_id;
	    $files = $request->file('upload');
	    if(!is_array($files)) $files = [$files];
	    foreach($files as $file){
		    if ($file->isValid()) {
			    $path = $file->store('originals', 's3');
			    $related_article = null;
			    if($article_id) $related_article = \App\Article::find($article_id);
			    $filemodel = new \App\File;
			    $filemodel->mime_type = mime($path);
			    $filemodel->extension = $file->extension();
			    $filemodel->filename = basename($path);
			    if($related_article)
				    $related_article->files()->save($filemodel);
			    else
				    $filemodel->save();
			}		    
	    }
	    $url = Storage::disk('s3')->url($path);
	    
		if($showresponse === true || $showresponse == 1)
		    return ['uploaded' => 1, 'filename' => basename($path), 'url' => $url];
	    else{
		    session(['status' => 'Files uploaded.']);
		    return redirect()->back();
	    } 
    }
/*
    public function upload(Request $request, $domain, $showresponse = true){

	    $files = $request->file('upload');
	    if(!is_array($files)) $files = [$files];
	    foreach($files as $file){
		    if ($file->isValid()) {
			    $origext = $file->getClientOriginalExtension();
			    $basename = str_slug(rtrim($file->getClientOriginalName(), ".$origext"));
			    $realext = $file->guessExtension();
			    $basename = strtolower("$basename.$realext");
			    $page_prefix = $request->input('page_prefix', '');
			    $basename = $page_prefix . $basename;
			    $i = 1;
			    $filename = public_path('files') . "/" . $page_prefix . $basename;
			    while(file_exists($filename)){
				    $basename = "$i-$basename";
				    $filename = public_path('files') . "/" . $page_prefix . $basename;
				    $i++;
			    }
			    $file->move(public_path('files'), $basename);
			}		    
	    }
		if($showresponse === true)
		    return ['uploaded' => 1, 'filename' => $basename, 'url' => "/files/medium/$basename"];
	    else{
		    session(['status' => 'Files uploaded.']);
		    return redirect()->back();
	    } 
    }
*/
    
    public function deleteFile(Request $request, $domain){
	    
	    $filename = $request->input('filename', false);
	    $filetype = $request->input('filetype', false);
	    if($filetype == 'physical'){
		    if(Storage::exists($filename)){
			    if(Storage::exists("_deleted_files/$filename")) Storage::move("_deleted_files/$filename", "_deleted_files/$filename-" . rand());
			    Storage::move($filename, "_deleted_files/$filename");
			    if(!Storage::exists($filename) && Storage::exists("_deleted_files/$filename")) return 1;
			    else return 0;
		    }
		    else return 0;		    
	    }
	    else if($filetype == 'model'){
			\App\Svg::where('path', $filename)->delete();
		    if(!\App\Svg::where('path', $filename)->first()) return 1;
		    else return 0;
	    }	    
	    
    }
    
}
