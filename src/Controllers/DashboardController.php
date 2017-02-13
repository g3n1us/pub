<?php
namespace G3n1us\Pub\Controllers;

use SaveUserRequest;
use DeleteUserRequest;
use Illuminate\Http\Request;
use Editor;

use Page;
use File;
use Article;
use User;
use Brand;
use Block;
use Area;
use UserGroup;

use DB;
use View;
use Storage;
use SmartyView;
use Auth;

class DashboardController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('auth')->except('getSeed');
        $this->request = $request;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex(Request $request)
    {
	    $data['brand'] = Brand::where('handle', BRAND_HANDLE)->first();
	    $data['brands'] = Brand::listable()->get();
	    $data['user'] = Auth::user();
// 	    dd($data['user']->groups);
// 	    dd($data['user']->groups()->create(['group' => 'admin']));
		$data['articles'] = [];
		return view("pub::dashboard.dashboard", $data);
    }
    
    public function getPages(Request $request)
    {
	    $data['brand'] = Brand::where('handle', BRAND_HANDLE)->first();
	    $data['brands'] = Brand::listable()->get();
	    $data['user'] = Auth::user();
		$data['articles'] = [];
		return view("pub::dashboard.dashboard_pages", $data);
    }
    
    public function getUsers(Request $request)
    {
	    $data['brand'] = Brand::where('handle', BRAND_HANDLE)->first();
	    $data['brands'] = Brand::listable()->get();
	    $data['user'] = Auth::user();
	    $data['users'] = User::paginate();
		$data['articles'] = [];
		return view("pub::dashboard.dashboard_users", $data);		
    }
    
    public function usermod(Request $request, User $user)
    {
	    $data['brand'] = Brand::where('handle', BRAND_HANDLE)->first();
	    $data['brands'] = Brand::listable()->get();
	    $data['moduser'] = $user;
	    $data['user'] = Auth::user();
	    $data['users'] = User::paginate();
		$data['articles'] = [];
		return view("pub::dashboard.dashboard_usermod", $data);				
    }
    
    public function usersave(SaveUserRequest $request, User $user)
    {
	    $user->name = $request->input('name', $user->name);
	    $user->email = $request->input('email', $user->email);
	    $groups = collect($request->groups)->unique()->transform(function($g){
		    return new UserGroup(['group' => $g]);
	    });
		$user->groups()->delete();
	    $user->groups()->saveMany($groups);
		if($user->save()) 
			return redirect()->back()->with(['message' => 'User Saved']);
		else 
			return redirect()->back()->with(['errors' => "I'm sorry, an error occurred. Please try again."]);
		
// 			return redirect()->back()->withErrors(['An error occurred']);
    }
    
    public function userdelete(DeleteUserRequest $request, User $user){
		if($user->delete()) 
			return redirect('/dashboard')->with(['message' => 'User Deleted']);
		else 
			return redirect()->back()->with(['errors' => "I'm sorry, an error occurred. Please try again."]);

	}    
    
    public function getArticles(Request $request)
    {
	    $data['brand'] = Brand::where('handle', BRAND_HANDLE)->first();
	    $data['brands'] = Brand::listable()->get();
	    $data['user'] = Auth::user();
	    $data['users'] = User::paginate();
		$data['articles'] = Article::withoutGlobalScopes(['is_approved'])->paginate();
		return view("pub::dashboard.dashboard_articles", $data);				
    }
    
    
    

    
    
    public function putAreaAdd(Request $request, Area $area){
	    $newblock = new Block;
// 	    $type = $request->input('type', 'content');
	    $type = $this->request->get('type', 'content');
	    
	    $typeidmap = [
		    'content' => 99,
		    'html' => 5,
		    'article_list' => 2,
		    'smarty' => 10,
		    'lead_story' => 69,
		    'google_sheets' => 69,
		    'data_api' => 69,
	    ];
// 	    $newblock->widgetTypeId = array_get($typeidmap, $type, 99);
	    $newblock->handle = $type;
	    $newblock->config = ['isDefault' => true];
	    $defaultcontents = config('concrete.block_types.'.$type.'.default_content', 'concrete.block_types.default.default_content');
	    foreach($defaultcontents as $k => $v){
		    $newblock->{$k} = $v;
	    }
// 	    $newblock->content = config('concrete.block_types.'.$newblock->type.'.default_content');
// 	    if(is_array($newblock->content)) $newblock->content = implode('~', $newblock->content);
	    return ['ok' => $area->blocks()->save($newblock)];
    }
    
    public function postMoveAreaBlocks(Request $request, Area $area){
	    $successes = 0;
	    $area_blocks = $request->input('area_blocks', []);
	    foreach($area_blocks as $aid => $blocks){
		    $thisarea = Area::findOrFail($aid);
		    foreach($blocks as $bid){
			    $thisblock = Block::findOrFail($bid);
			    $thisarea->blocks()->save($thisblock);
		    }
		    
		    $thisconfig = $thisarea->config;
		    $thisconfig->block_order = $blocks;
		    $thisarea->config = $thisconfig;
		    if($thisarea->save()) 
			    $successes++;
		    
	    }
	    return ['ok' => $successes == count($area_blocks)];
	    
/*
	    $res = $area->blocks->pluck('id');
	    $res = $area->config;
*/
/*
	    $blockorder = $area->blocks->pluck('id');
	    $newblockorder = $request->block_order;
// 	    dd($newblockorder);
	    $config = $area->config;
	    $config->block_order = $newblockorder;
	    $area->config = $config;
	    return ['ok' => $area->save()];
*/
    }
    
    
    
    
    
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function getSeed(){
	    
	    die();
	    
	    $content1 = '<div class="container margin-top40">
			<div class="row">
				<div class="col-md-4">
					<div class="panel panel-default">
						<div class="panel-body text-center">
							<img src="/themefile/unify/images/home/Axion_TheBattery_icon_circle.svg">
							<h5>The Battery</h5>
							<p class="text-left">
								lksdjflksd lksdjf  lskjdflksjdf lskjdf lkjdsflksdf lsdjfl. Tlsjljf lsjdfj lskjflsd. lksdjflksd lksdjf  lskjdflksjdf lskjdf lkjdsflksdf lsdjfl.
							</p>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="panel panel-default">
						<div class="panel-body text-center">
							<img src="/themefile/unify/images/home/Axion_Products_icon_circle.svg">
							<h5>Products</h5>
							<p class="text-left">
								lksdjflksd lksdjf  lskjdflksjdf lskjdf lkjdsflksdf lsdjfl. Tlsjljf lsjdfj lskjflsd. lksdjflksd lksdjf  lskjdflksjdf lskjdf lkjdsflksdf lsdjfl.
							</p>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="panel panel-default">
						<div class="panel-body text-center">
							<img src="/themefile/unify/images/home/Axion_Partnerships_icon_circle.svg">
							<h5>Partnerships</h5>
							<p class="text-left">
								lksdjflksd lksdjf  lskjdflksjdf lskjdf lkjdsflksdf lsdjfl. Tlsjljf lsjdfj lskjflsd. lksdjflksd lksdjf  lskjdflksjdf lskjdf lkjdsflksdf lsdjfl.
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>';
	    $content2 = '<div class="container small margin-top40">
			<div class="row">
				<div class="col-md-12">
							<a href="index.html"><img id="logo-footer" class="footer-logo" src="/themefile/unify/assets/img/logo2-default.png" alt=""></a>
							<p>About Unify dolor sit amet, consectetur adipiscing elit. Maecenas eget nisl id libero tincidunt sodales.</p>
							<p>Duis eleifend fermentum ante ut aliquam. Cras mi risus, dignissim sed adipiscing ut, placerat non arcu.</p>
						</div>					
				</div>
		</div>';
		
	    
$makeme = 'INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `config`, `remember_token`, `created_at`, `updated_at`)
VALUES
	(1, \'Sean Bethel\', \'tech@jmbdc.com\', \'$2y$10$ZPpEeUhYqDweZ2KNQ/mN1uWcywtwWXn4IJrC3AU/C3RovmPEhTt.q\', \'admin\', \'\', NULL, \'2016-05-23 21:24:29\', \'2016-05-23 21:24:29\')';
	    
	    $basepath = base_path();
	    exec("cd $basepath && php artisan migrate:refresh");
		DB::statement($makeme);	    

	    $site = new \App\Site;
	    $site->name = 'Axion';
	    $site->handle = 'axion';
	    $site->theme = 'unify';
	    $site->save();

	    $page = new \App\Page;
	    $page->name = 'Home';
	    $page->handle = 'home';
	    $page->path = '/';
	    $site->pages()->save($page);
	    
	    $area = new \App\Area;
	    $area->name = 'Main';
	    $area->handle = 'main';
	    $page->areas()->save($area);
	    
	    $block = new \App\Block;
	    $block->type = 'content';
	    $block->content = $content1;
	    $area->blocks()->save($block);
	    
	    $block2 = new \App\Block;
	    $block2->type = 'content';
	    $block2->content = $content2;
	    $area->blocks()->save($block2);
	    
/*
	    $bv = new \App\BlockVersion;
	    $bv->type = $block->type;
	    $bv->content = $block->content;
	    $block->block_versions()->save($bv);
*/

/*
	    $site = new \App\Site;
	    $site->name = 'Axion';
	    $site->handle = 'axion';
	    $site->save();
*/

    }
    
    
}



//     {"is_global":false,"area_wrap":{"before":"","after":""},"block_wrap":{"before":"<div class=\"col-md-12\">","after":"<\/div>"},"area_classes":"row ccm_area_wrapper"}
/*
    public function postUpload(Request $request, $showresponse = true){

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
// 			    $filename = storage_path('app') . "/" . $page_prefix . $basename;
			    $filename = $page_prefix . $basename;
			    while(Storage::exists($filename)){
				    $basename = "$i-$basename";
				    $filename = $page_prefix . $basename;
				    $i++;
			    }
			    Storage::put($filename, file_get_contents($file->getRealPath()));
			}		    
	    }
		if($showresponse === true)
		    return ['uploaded' => 1, 'filename' => $basename, 'url' => "/files/medium/$basename"];
	    else{
		    return redirect()->back()->with('message', 'Files uploaded');
	    } 
    }
*/
/*
    
    public function getCklist(Request $request = null){
	
		$files = collect(Storage::files())->filter(function($f){return !starts_with($f, '.');});
		$files->transform(function($key, $value){
			$is_image = str_contains(mime($key), "image");
			return [
				"image" => $is_image ? "/files/medium/$key" : "/themefile/$key", 
				"large" => $is_image ? "/files/large/$key" : "/themefile/$key", 
				"medium" => $is_image ? "/files/medium/$key" : "/themefile/$key", 
				"small" => $is_image ? "/files/small/$key" : "/themefile/$key",
				"filename" => $key,
				"fullurl" => url("/files/large/$key"),				
				"mime"     => mime($key),
				"thumb" => (mime($key) == "application/pdf") ? "/img/pdf_placeholder.png" : "/files/thumb/$key",				
				"filesize" => Storage::size($key),
				"last_modified" => Storage::lastModified($key),
				"filetype" => "physical",
			];				
		});
		//$returnedfiles = array_merge($modelfiles->all(), $dynamicfiles->all(), $files->all());
		$divider = [[
				"image" => null, 
				"large" => null, 
				"medium" => null, 
				"thumb" => null,
				"small" => null,
				"filename" => null,
				"mime"     => null,
				"filesize" => null,
				"last_modified" => null,
				"filetype" => "divider",
			]];
		$returnedfiles = array_merge($files->all(), $divider);
	
		return $returnedfiles;   
    }
*/
    
    
    
/*
    public function getPagelist(Request $request, \App\Brand $brand, $tab = 'files'){
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
			SmartyView::display('filemanager/filemanager.tpl', [
			    'tab'  => $tab,
		    ]);		    
	    }
    }
*/
    
/*
    public function deleteBlock(Request $request, \App\Brand $brand, $block){
// 	    dd($block->block);
	    return ['ok' => $block->block->delete()];
    }
*/