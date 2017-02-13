<?php
namespace G3n1us\Pub\Controllers;

use Illuminate\Http\Request;
use Block;
use BlockMorpher;

class BlockController extends BaseController
{
	public function __construct(Request $request){
        $this->middleware('auth')->except('index', 'show');
	}
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Block::paginate();
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
	    $newblock = new Block;
	    $type = $request->get('type', 'content');	    
	    $newblock->handle = $type;
	    $newblock->config = ['isDefault' => true];
	    $defaultcontents = config('pub.block_types.'.$type.'.default_content', 'pub.block_types.default.default_content');
	    foreach($defaultcontents as $k => $v){
		    $newblock->{$k} = $v;
	    }
	    return ['ok' => $area->blocks()->save($newblock)];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($block)
    {
		return $block->content;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($block)
    {
		return call_user_func_array($block->edit_form, [$block]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $block)
    {
		$model = $request->input('model', []);
		$configs = $request->input('config', []);
		$saveblock = $block->originalblock;
		foreach($configs as $k => $v){
			if($v)
				Block::where('id', $saveblock->id)->update([$k => $v]);
		}
		foreach($model as $field => $value){
			$saveblock->{$field} = $value;
		}
		return ['ok' => $saveblock->save()];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($block)
    {
	    return ['ok' => $block->block->delete()];
    }
}

