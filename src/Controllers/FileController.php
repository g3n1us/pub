<?php
namespace G3n1us\Pub\Controllers;

use Illuminate\Http\Request;
use Page;
use File;
use Article;

use DB;
use View;
use Storage;
use SmartyView;
use Auth;

class FileController extends BaseController
{
	
	public function __construct(Request $request){
	        $this->middleware('auth')->except('show');
	}
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $tab = 'files'){
	    if(!in_array($tab, ['files', 'photos', 'pages', 'articles']))
		    abort(404);
	    if($request->page) {
		    $classname = studly_case(str_singular($tab));
		    $return = [];
		    if($tab == 'files')
			    $return['data'] = File::latest()->paginate();
		    else if($tab == 'pages')
			    $return['data'] = Page::select('name', 'url', 'id')->latest('id')->paginate();
		    else if($tab == 'articles'){
			    $return['data'] = Article::latest()->paginate();
		    }   
		    ob_start();
		    echo($return['data']->links());
		    $return['links'] = ob_get_clean();
		    return collect($return);
	    }
	    else{
		    return view('pub::filemanager.filemanager', [
			    'tab'  => $tab,
		    ]);
		    
	    }
    }    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
	    $showresponse = $request->show_response;
		$article_id = $request->article_id;
	    $files = $request->file('upload');
	    if(!is_array($files)) $files = [$files];
	    foreach($files as $file){
		    if ($file->isValid()) {
			    $path = $file->store('originals', config('pub.filesystem'));
			    $related_article = null;
			    if($article_id) $related_article = Article::find($article_id);
			    $filemodel = new File;
			    $filemodel->mime_type = mime($path);
			    $filemodel->extension = $file->extension();
			    $filemodel->filename = basename($path);
			    if($related_article)
				    $related_article->files()->save($filemodel);
			    else
				    $filemodel->save();
			}		    
	    }
	    $url = Storage::disk(config('pub.filesystem'))->url($path);
		if($showresponse === true || $showresponse == 1)
		    return ['uploaded' => 1, 'filename' => basename($path), 'url' => $url];
	    else{
		    return redirect()->back()->with(['message' => 'File(s) uploaded']);
	    } 
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($file)
    {
	    return response(file_get_contents($file->url))->header('Content-Type', $file->mime_type);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    
}
