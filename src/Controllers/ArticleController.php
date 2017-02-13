<?php
namespace G3n1us\Pub\Controllers;

//use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SmartyView;
use Auth;
use BlockMorpher;
use Carbon\Carbon;


use Article;
use ArticleContent;
use Tag;
use Author;
use Workflow;
use User;

class ArticleController extends BaseController
{
	public function __construct(Request $request){
        $this->middleware('auth')->except('index', 'show');
        parent::__construct($request);
	}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
	    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
	    $article = new Article;
	    $article->title = $request->title;
	    $article->short_title = $request->title;
	    $article->summary = $request->summary;
	    $article->slug = $request->slug;
	    $article->pub_date = Carbon::now();
	    $article->save();
	    $content = new ArticleContent;
	    $article->content()->save($content);
		if($request->isXmlHttpRequest()) return $article;
	    else return redirect($article->url);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $article)
    {
/*
	    dd($article);
	    $article = Article::findOrFail($article);
*/
// 	    dd($article);
		if($request->format == 'json') return $article;
	    $data['articles'] = collect([$article]);
	    $data['article'] = $article;
	    $data['user'] = Auth::user();
	    $data['brand'] = $this->brand;
	    $data['heading'] = $this->brand->name . ' | ' . $article->title;
		return view('pub::article', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $article)
    {
// 	    dd($article->workflow);
	    if(!$article->pub_date) $article->pub_date = \Carbon\Carbon::now();
	    if(!$article->author_display) $article->author_display = auth()->user()->name;
		// import old tags system
//		$article->legacy_sections;
		$article->load('tags', 'files', 'authors');
		if(!$article->workflow) $article->workflow = new Workflow;
	    $data['article'] = $article;
	    $data['articles'] = collect([$article]);
	    $data['article_files'] = $article->files->transform(function($f){
		    if($f->pivot->metadata == "null") $f->pivot->metadata = "{}";
		    $f->pivot->metadata = collect(json_decode($f->pivot->metadata));
		    return $f;
	    });
// 	    dd($data['article_files']);
	    $data['user'] = Auth::user();
	    $data['request'] = $request;
	    $data['brand'] = $this->brand;
	    $data['session'] = session();
	    
	    $data['authors'] = Author::orderBy('firstname')->get();
	    return view('pub::article_edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $article)
    {
// 	    dd($request->workflow);
// 	    dd($article);
// 	    $article = ($article == $slug) ? $article : $slug;
	    if(!$article || !$article->id) {
		    $article = new Article;
		    $article->save();
		    $content = new ArticleContent;
		    $article->content()->save($content);
	    }
	    else if(!Article::withoutGlobalScopes(['is_approved'])->find($article->id)){
		    
		    Article::firstOrCreate(['id' => $article->id]);
		    //means that the article is being restored from an article version
		    $article->save();
	    }
	    $content = $article->content()->firstOrNew([]);
	    $content->body = $request->input('content.body');
	    if($content->body)
		    $content->save();


		if($request->workflow){
			if(!$article->workflow)
				$article->workflow()->create([]);
			
			$workflow = $request->workflow;
			$notes = collect($workflow['notes']);
			$notes = $notes->filter(function($v){
				return !empty($v);
			})->values();
			$notes = $notes->transform(function($v){
				$new = ['user' => auth()->user(), 'timestamp' => \Carbon\Carbon::now(), 'message' => $v];
				preg_match_all("/(@\w+)/", $v, $matches);
				$new['mentions'] = array_unique($matches[1]);
				foreach($new['mentions'] as $k => $m){
					$new['mentions'][$k] = User::where('username', ltrim($m, '@'))->first();
				}
				return $new;
			});
			$workflow['notes'] = $notes->merge(object_get($article, 'workflow.notes', []));
			$article->workflow()->update($workflow);
		}

		$tags_sync = [];		
		if($request->tags){
			foreach($request->tags as $reqtag){
				$newtag = Tag::firstOrNew(['handle' => str_slug($reqtag)]);
				$newtag->name = $reqtag;
				$newtag->save();
				$tags_sync[] = $newtag->id;
			}
		}
		$article->tags()->sync($tags_sync);
		
		$article->tags;
		if($request->authors)
			$article->authors()->sync($request->authors);
		
	    $article->summary = $request->summary;
	    $article->title = $request->title;
	    $article->short_title = $request->short_title ?: $request->title;
	    $article->subtitle = $request->subtitle;
	    $article->slug = $request->slug;

	    $article->pub_date = strtotime($request->pub_date);
	    $article->author_display = $request->author_display;
	    $article->approved = $request->approved;
	    $article->save();
	    $article->flush_cache();

		if($request->isXmlHttpRequest()) return $article;
	    else return redirect($article->url);
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
