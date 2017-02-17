<?php
namespace G3n1us\Pub\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use Image;
use Storage;
use View;
use DateTime;
use SmartyView;
use Cache;
use Socialite;
use DB;
use Article;
use File;
use Brand;
use Tag;
use ArticleContent;
use \Carbon\Carbon as Carbon;


class PublicController extends BaseController
{
	public function __construct(Request $request){
		parent::__construct($request);
		$this->middleware('cors', ['only' => ['returnContents']]);
	}
	
    public function index(Request $request){
		$data['viewname'] = 'brands.'.$this->brand->slug.'.home';
	    $data['brand'] = $this->brand;
	    $data['user'] = Auth::user();
		$data['heading'] = "Home";   
		$data['articles'] = Cache::remember('home_article_list', 60, function(){
			return Article::limit(20)->get();
		});
		return view('pub::home', $data);
    }
    
    public function showAuthor(Request $request, $author = null){
	    if(!$author){
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
// 		    return response(SmartyView::fetch('authors.tpl', $data));
		    
	    }
	    $author = Cache::remember('author_data_'.$request->url(), 60, function() use($author){
	    	return Author::where('handle', $author)->orWhere('displayname', $author)->with('articles')->firstOrFail();
	    });
	    
	    // if(!$author) return \App\Author::paginate();

		$data['viewname'] = 'brands.'.$this->brand->slug.'.home';
	    $data['brand'] = $this->brand;
	    $data['user'] = Auth::user();
	    
	    $data['author'] = $author;
	    $data['extra'] = 'author';
	    $data['articles'] = $author->articles;
		$data['heading'] = "Articles by {$author->displayname}";
		if($request->format == 'json') return array_except($data, 'user');
		return view('pub::article_list', $data);		
// 	    return response(SmartyView::fetch('article_list.tpl', $data));
    }
    
    public function fileSaveAttributes(Request $request, File $file, Article $article = null){
	    if($request->has('_remove')){
		    $article->files()->detach($file->id);
		    return redirect()->back()->with('message', 'File removed from article');
	    }
	    if($request->has('metadata->lead_photo')){
		    $existingleads = $article->files()->wherePivot('metadata->lead_photo', "true")->get();
		    foreach($existingleads as $existinglead){
			    $existinglead->pivot->update(['metadata->lead_photo' => "false"]);
		    }
	    }
	    $article_file = $article->files()->find($file->id);
	    if(!$article_file){
			$article->files()->save($file);
	    }
	    $article_file = $article->files()->find($file->id);
	    $pivot = $article_file->pivot;
	    if($pivot->metadata == "null") $pivot->update(["metadata" => "{}"]);
		$pivot->update($request->except('_token'));
		$article->flush_cache();
		$file->flush_cache();
	    return redirect()->back()->with('status', 'Saved');
    }
    
	public function deleteFile(Request $request, File $file, Article $article = null){
		$article->files()->detach($file->id);
		$file->delete();
		return redirect()->back()->with('message', 'The file has been deleted');
	}    
    
    public function showSearch(Request $request, $term = null){
		$data['viewname'] = 'brands.'.$this->brand->slug.'.home';
	    $data['brand'] = $this->brand;
	    $data['user'] = Auth::user();
	    
	    $term = $request->get('q', $term);
	    if(!$term) {
		    $data['articles'] = collect([]);
		    return view('pub::search', $data);
	    }
	    
	    $data['heading'] = "Searching for \"$term\"";
	    $article_contents = ArticleContent::where('body', 'like', "%$term%")->with('article')->limit(500)->pluck('article_id');

	    $data['articles'] = Article::where('title', 'like', "%$term%")->orWhereIn('id', $article_contents)->orderBy('pub_date', 'desc')->paginate();
		return view('pub::article_list', $data);

// 	    return response(SmartyView::fetch('article_list.tpl', $data));
    }
    
    public function showTag(Request $request, $tag = null){
		$data['viewname'] = 'brands.'.$this->brand->slug.'.home';
	    $data['brand'] = $this->brand;
	    $data['user'] = Auth::user();
	    $foundtag = Tag::where('handle', $tag)->orWhere('name', $tag)->firstOrFail();
	    $data['articles'] = $foundtag->articles()->paginate();
	    $data['heading'] = "Articles Tagged with $tag";
		if($request->format == 'json') return array_except($data, 'user');
		return view('pub::article_list', $data);
// 	    return response(SmartyView::fetch('article_list.tpl', $data));
	    
    }
    
    public function byDate(Request $request, $date = null, $range = null, $order = 'desc'){
	    $data['extra'] = 'years';
	    $data['years'] = range(1995, date('Y'));
		    $data['request'] = $request;	
	    
	    if(!$date){
		    $data['user'] = auth()->user();	
		    $data['brand'] = $this->brand;	
			$data['heading'] = "By Date";	
		    
		    $data['articles'] = collect([]);
// 		    dd($data['authors']);
			if($request->format == 'json') return array_except($data, 'user');
			return view('pub::article_list', $data);
// 		    return response(SmartyView::fetch('article_list.tpl', $data));
		    
	    }
	     
	    $date = new Carbon($date);
		$data['viewname'] = 'brands.'.$this->brand->slug.'.home';
	    $data['brand'] = $this->brand;
	    $data['user'] = auth()->user();
	    if($range == 'year') {
		    $data['articles'] = Article::whereYear('pub_date', '=', $date->year)->orderBy('pub_date', $order)->paginate();
		    $data['heading'] = "Articles from {$date->year}";
	    }
	    else if($range == 'month') {
		    $data['articles'] = Article::whereMonth('pub_date', '=', $date->month)->whereYear('pub_date', '=', $date->year)->orderBy('pub_date', $order)->paginate();
		    $data['heading'] = "Articles from {$date->format('F')} {$date->year}";		    
	    }
	    else {
		    $data['articles'] = Article::whereDate('pub_date', '=', $date->toDateString())->orderBy('pub_date', $order)->paginate();
		    $data['heading'] = "Articles from {$date->format('F jS, Y')}";
	    }
		return view('pub::article_list', $data);	    
// 	    return response(SmartyView::fetch('article_list.tpl', $data));
	    
    }
    
    public function brand(Request $request){
		$data['viewname'] = 'brand';
		$data['brand'] = $brand;

		$data['loggedin'] = $this->loggedin;

		if(array_get($_GET, 'print', false)) 
			return response()->view('print.brand', $data)->header('Content-Type', 'image/svg+xml');
			
	    return response()->view('brand', $data);
    }
    
    
    public function brandsub(Request $request, Page $page){
		$data['viewname'] = 'brandsub';

		if(array_get($_GET, 'page_version', false)) $page = Page::find(array_get($_GET, 'page_version', false));
		
	    $data['page'] = $page;
	    $data['brand'] = $page->brand;

		$data['slug'] = basename($page->path);
		
		$data['replace_svgs'] = true;

		if(array_get($_GET, 'print', false)) 
			return response()->view('print.brandsub', $data)->header('Content-Type', 'image/svg+xml');

	    return response()->view('brandsub', $data);
    }
    
    
    public function printbrandsub(Request $request, Page $page){
		$data['viewname'] = 'brandsub';
		
		if(array_get($_GET, 'page_version', false)) $page = Page::find(array_get($_GET, 'page_version', false));
		
	    $data['page'] = $page;
	    
	    $data['brand'] = $page->brand;
	    
	    $data['request'] = $request;

		$data['slug'] = basename($page->path);
		
		$data['replace_svgs'] = true;

		if(array_get($_GET, 'print', false)) 
			return response()->view('print.brandsub', $data)->header('Content-Type', 'image/svg+xml');

	    return response()->view('printbrandsub', $data);
    }
    
    
    public function returnContents(Request $request){

		$url = $request->input('url');
		$base64 = (bool)$request->input('base64', false);
		$datauri = (bool)$request->input('datauri', false);
		$testurl = explode('?', $url);
		$testurl = head($testurl);
		$extraquery = str_contains($url, '?') ? "&is_ajax=true" : "?is_ajax=true";
		$url = $url . $extraquery;
		if(str_contains($testurl, 'svg')) {
			$mime = 'image/svg+xml';
			$contents =  starts_with($url, 'http') ? file_get_contents($url) : file_get_contents(url($url));
		}
		else if(ends_with($testurl, ['.jpg', '.jpeg'])) {
			$mime = 'image/jpeg';
			$contents = starts_with($url, 'http') ? file_get_contents($url) : file_get_contents(url($url));
			
		}
		else if(ends_with($testurl, ['.png'])) {
			$mime = 'image/png';
			$contents = starts_with($url, 'http') ? file_get_contents($url) : file_get_contents(url($url));
		}
		
		if($base64) return base64_encode($contents);
		if($datauri) return "data:$mime;base64," . base64_encode($contents); 
		return $contents;			
	    
    }
    
    
    
    public function smallimage(Request $request, $image){
	    // Do we still need the reflashes below??
	    $request->session()->reflash();
	    
	    $imagelc = strtolower($image);
		if(!ends_with($image, ['png', 'jpg', 'jpeg'])) 
			return redirect("/files/$image");
	    
		$img = Image::make(public_path('files') . "/$image")->widen(500);

		$ext = 'jpg';
		if(ends_with($imagelc, 'png')) $ext = 'png';
		if(ends_with($imagelc, 'jpeg')) $ext = 'jpg';
		
		$img->save(public_path('files') . "/small/$image", 60);
	    return $img->response($ext, 60);
    }
    
    public function mediumimage(Request $request, $image){
	    
	    $request->session()->reflash();
	    $imagelc = strtolower($image);
		if(!ends_with($image, ['png', 'jpg', 'jpeg'])) 
			return redirect("/files/$image");
	    
		$img = Image::make(public_path('files') . "/$image")->widen(1000);
		$ext = 'jpg';
		if(ends_with($imagelc, 'png')) $ext = 'png';
		if(ends_with($imagelc, 'jpeg')) $ext = 'jpg';
		
		$img->save(public_path('files') . "/medium/$image", 60);
	    return $img->response($ext, 60);
    }
    
    public function thumbimage(Request $request, $image){
	    $request->session()->reflash();
	    $imagelc = strtolower($image);
		if(!ends_with($image, ['png', 'jpg', 'jpeg'])) 
			return redirect("/files/$image");
	    
		$img = Image::make(public_path('files') . "/$image")->widen(150);
		
		$ext = 'jpg';
		if(ends_with($imagelc, 'png')) $ext = 'png';
		if(ends_with($imagelc, 'jpeg')) $ext = 'jpg';
		
		$img->save(public_path('files') . "/thumb/$image", 60);
	    return $img->response($ext, 60);
    }
    
    public function dynamicSvg(Request $request, $template, $imgdata = false){
	    $request->session()->reflash();
	    if(Auth::check()){
		    $pathstring = last(explode(".com", $request->fullUrl()));
		    
			$curl = curl_init();
			curl_setopt_array($curl, array(
			    CURLOPT_RETURNTRANSFER => 1,
			    CURLOPT_URL => url($pathstring),
			));
			$resp = curl_exec($curl);
			$info = curl_getinfo($curl);
			curl_close($curl);

			if($info['http_code'] < 400 && str_contains($info['content_type'], 'svg')){
				\App\Svg::firstOrCreate(['path' => $pathstring]);
			}
		    
	    }
	    
	    if(!$brand->id) {
		    $brand = $request->input('brand', config('app.default-brand', 'weekly-standard'));
		    $brand = Brand::where('handle', $brand)->first();
	    }

		$data['brand'] = $brand;
	    if(is_string($imgdata)) $imgdata = preg_replace('/\.svg$/', '', $imgdata);

	    if($imgdata === false || in_array($imgdata, ['-', ' ', 'false', 'none'])) $imgdata = $request->all();
	    else if(starts_with($imgdata, 'gtable')) $imgdata = gproxy(config('app.default-google-sheet-key'), str_replace("gtable-", "", $imgdata));
	    else $imgdata = json_decode($imgdata, true);

	    $template = preg_replace('/\.svg$/', '', $template);

	    $data['template'] = $template;
	    $data['imgdata'] = $imgdata;
	    $data['request'] = $request;

	    return response()->view("svgtemplates.$template", $data)->header('Content-Type', 'image/svg+xml');
    }
    
    public function imagefallback(Request $request, $anything, $filename){
//	    $request->session()->reflash();
	    return response(Storage::get($filename))->header('Content-Type', 'image/svg+xml');
    }
    
    
    public function dittopage(Request $request, DittoPage $dittopage){
		$data['viewname'] = 'brands.'.$brand->slug.'.home';
	    $data['brand'] = $brand;
	    $data['user'] = Auth::user();
	    $data['articles'] = false;
// 	    $dittopage->load('metadata');
	    $data['page'] = $dittopage;
		return view('pub::'.$dittopage->template, $data);
// 	    return response(SmartyView::fetch($dittopage->template, $data));
	    
    }
    
}
