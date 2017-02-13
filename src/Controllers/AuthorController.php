<?php
namespace G3n1us\Pub\Controllers;

use Illuminate\Http\Request;

use Cache;
use Auth;
use SmartyView;

use Author;

class AuthorController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
	    $data['user'] = Auth::user();	
	    $data['brand'] = $this->brand;	
		$data['heading'] = "Our Authors";		    	    	    
	    $data['authors'] = Author::whereNotNull('bio')
		    ->where('bio', '<>', '')
		    ->where('mugshot', '<>', '')
		    ->orderBy('lastname')
		    ->paginate();
	    $data['articles'] = collect([]);
		if($request->format == 'json') return array_except($data, 'user');
		return view('pub::authors', $data);
// 	    return response(SmartyView::fetch('authors.tpl', $data));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $author)
    {
	    $author = Cache::remember('author_data_'.$request->url(), 60, function() use($author){
	    	return Author::where('handle', urldecode($author))->orWhere('displayname', urldecode($author))->with('articles')->firstOrFail();
	    });
//		$data['viewname'] = 'brands.'.$brand->slug.'.home';
	    $data['brand'] = $this->brand;
	    $data['user'] = Auth::user();
	    $data['author'] = $author;
	    $data['extra'] = 'author';
	    $data['articles'] = $author->articles()->paginate();

		$data['heading'] = "Articles by {$author->displayname}";
		if($request->format == 'json') 
			return array_except($data, 'user');
		return view('pub::article_list', $data);
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
