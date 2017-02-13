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
	    $data['user'] = Auth::user();
		$data['articles'] = [];
		return view("pub::dashboard.dashboard", $data);
    }
    
    public function getPages(Request $request)
    {
	    $data['user'] = Auth::user();
		$data['articles'] = [];
		return view("pub::dashboard.dashboard_pages", $data);
    }
    
    public function getUsers(Request $request)
    {
	    $data['user'] = Auth::user();
	    $data['users'] = User::paginate();
		$data['articles'] = [];
		return view("pub::dashboard.dashboard_users", $data);		
    }
    
    public function usermod(Request $request, User $user)
    {
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
	    $newblock->handle = $type;
	    $newblock->config = ['isDefault' => true];
// 	    $defaultcontents = config('pub.block_types.'.$type.'.default_content', 'pub.block_types.default.default_content');

	    $defaultcontents = block_config($type, 'default_content');
	    foreach($defaultcontents as $k => $v){
		    $newblock->{$k} = $v;
	    }
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
    }
    
    
    
    

    
    
}


