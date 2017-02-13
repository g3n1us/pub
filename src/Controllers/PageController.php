<?php
namespace G3n1us\Pub\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SavePageRequest;

use Page;
use Tag;
use Article;
use Auth;
use SmartyView;

use Area;

class PageController extends BaseController
{
	public function __construct(Request $request){
        $this->middleware('auth')->except('index', 'show');
        
        parent::__construct($request);
	}
	
/*
	private function resolvePage($pathstring){
			if(is_numeric($pathstring)) return Page::findOrFail($pathstring);
			$definitive_page = Page::where('url', $pathstring)->first();
			if($definitive_page) 
				return $definitive_page;
			$alias = false;	
			foreach(\App\PageAlias::get() as $testpage){
				$string = str_replace('/', '\/', $testpage->alias);
				$string = str_replace('\\/', '\/', $string);
				$string = "/$string/";
				$matched = @preg_match($string, '/'.$route->page, $matches);
				if($matched && $testpage->page){
					$alias = $testpage->page;
					break;
				}
			}
			if($alias) return $alias;
			else abort(404);
	}
*/
	
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Page::paginate();
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
    public function store(SavePageRequest $request)
    {
//		if($request->url){
			$newpage = new Page;
			$newpage->url = $request->url;
			$newpage->description = $request->description;
			$newpage->name = $request->name;
			$newpage->template = 'page_default';
			$newpage->config = [];
	        if($request->ajax())
		        return ['ok' => $newpage->save()];
	        $saved = $newpage->save();
	        if($saved)
				return redirect($request->url)->with('message', 'Saved');
			else 
				return redirect()->back()->with('error', 'A Problem Occurred');
			
//		}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $page)
    {
   		$data['viewname'] = 'brands.'.$this->brand->slug.'.home';
	    $data['brand'] = $this->brand;
	    $data['user'] = Auth::user();
	    $data['articles'] = false;
// 	    $dittopage->load('metadata');
	    $data['page'] = $page;
	    $templatename = ends_with($page->template, '.tpl') ? str_replace('.tpl', '', $page->template) : $page->template;
	    return view('pub::'.$templatename, $data);
// 	    return response(SmartyView::fetch($page->template, $data));
    }    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($brand, $page)
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
    public function update(SavePageRequest $request, $brand, $page){
	    $configs = collect($request->all())->filter(function($k,$v){
		    return str_contains($v, 'config->');
	    });
		$page->update($configs->toArray());
		$page->url = $request->url;
		$page->description = $request->description;
		$page->name = $request->name;
		$page->template = 'page_default.tpl';
        if($request->ajax())
	        return ['ok' => $page->save()];
        $saved = $page->save();
        
		$tags_sync = [];		
		if($request->tags){
			foreach($request->tags as $reqtag){
				$newtag = Tag::firstOrCreate(['handle' => str_slug($reqtag)]);
				$newtag->name = $reqtag;
				$newtag->save();
				$tags_sync[] = $newtag->id;
			}
		}
		$page->tags()->sync($tags_sync);
	    $page->flush_cache();
        if($saved)
			return redirect($page->url)->with('message', 'Saved');
		else 
			return redirect()->back()->with('error', 'A Problem Occurred');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($brand, $page)
    {
	    return ['ok' => $page->delete()];
    }
}
