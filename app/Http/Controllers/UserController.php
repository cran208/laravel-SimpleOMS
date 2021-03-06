<?php namespace App\Http\Controllers;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\UserField;
use Session;
use URL;
use App\Services\Authorize;
class UserController extends Controller {
	protected $service,$user,$permission;
	protected $errorMessage = [];

	public function __construct(UserService $service,Authorize $permission){
		$this->service = $service;
		$this->user = Auth::user();
		$this->permission=$permission;
	}

	public function getUserLog($id,UserField $fieldService){
		$user = $this->service->listOne($id);
		$data['user']=$user;
		$data['actions']=['backpage'];
		return view('detail.userlog')->with($data);
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request,UserField $fieldService)
	{
		$arrRequest = $request->all();

		$fieldService->currentRole($this->user->auth);
		$fieldService->currentStatus('');
		$data=array();
		$data['title']='用户';
		$data['stitle']='';
		$data['class']='user';
		$data['field']=$fieldService;
		$data['data']=$this->service->lists($arrRequest,'',20);
		$data['actions']=array_only($this->permission->get($this->user->auth),['UserController@create','UserController@destroy']);
		Session::put('index_url',URL::full());
		return view('home')->with($data)->withInput($request->flash());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(UserField $fieldService)
	{
		$fieldService->currentRole($this->user->auth);
		$fieldService->currentStatus('');
		$data=[];
		$data['class']='user';
		$data['field']=$fieldService;
		$data['actions']=array_only($this->permission->get($this->user->auth),['UserController@store']);
		$data['actions'][]='backpage';
		return view('create.user')->with($data);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request,UserField $fieldService)
	{
		//
		$fieldService->currentRole($this->user->auth);
		$fieldService->currentStatus('');
		$fields = $fieldService->parseValidator('add');
		$fields['uid']='required|alpha_dash|min:5';
		$fields['password']='required|confirmed|alpha_dash|min:6';
		$this->validate($request,$fields);

		if($this->service->create($request->all())){
			return redirect('user')->withSuccess('添加成功');
		}
		else{
			return redirect()->back()->withErrors('操作失败');
		}
	}
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id,UserField $fieldService)
	{
		//

		$user = $this->service->listOne($id);
		$fieldService->currentRole($this->user->auth);
		$fieldService->currentStatus('');
		$data['class']='user';
		$data['user']=$user;
		$data['field']=$fieldService;
		$data['actions']=array_only($this->permission->get($this->user->auth),['UserController@store']);
		$data['actions'][]='backpage';
		return view('detail.user')->with($data);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id,Request $request,UserField $fieldService)
	{
		//
		$fieldService->currentRole($this->user->auth);
		$fieldService->currentStatus('');
		$fields = $fieldService->parseValidator('edit');
		$fields['password']='confirmed|alpha_dash|min:6';
		$this->validate($request,$fields);

		if($this->service->edit($request->all(),$id)){
			return redirect()->back()->withSuccess('更新成功');
		}
		else{
			return redirect()->back()->withErrors('操作失败');
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  string  $id
	 * @return Response
	 */
	public function destroy(Request $request)
	{
		//
		$arrId = $request->input('id',[]);
		$i=0;
		if(empty($arrId)){
			return redirect()->back();
		}
		foreach ($arrId as $v) {
			if($this->service->delete($v)){
				$i++;
			}
		}
		return redirect()->back()->withSuccess('成功删除 '.$i.' 条数据');
	}

}
