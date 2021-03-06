@extends('base')
@section('content')
	
<div class="container">
		@include('partials.info')

	<h2>设备{{$product->pid}} <small>{{$field->statusName($product->pstatus)}}</small></h2><input type="hidden" name="obj_id" value="{{$product->id}}">
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active"><a href="#detail" aria-controls="detail" role="tab" data-toggle="tab">详细信息</a></li>
		<li role="presentation"><a href="#logs" aria-controls="logs" role="tab" data-toggle="tab">操作记录</a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="detail" role="tabpanel">
			<br>
			<form action="{{url('product/'.$product->id)}}" id="form" method="POST" class="form-horizontal">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="hidden" name="_method" value="PUT">
				<div class="row">
					{!! $field->editFieldHTML('pid','设备号',$product) !!}
					{!! $field->editFieldHTML('house','库存名',$product) !!}
					{!! $field->editFieldHTML('belongsToSupply_name','库存名',$product) !!}

				</div>
				<div class="row">
					{!! $field->editFieldHTML('pstatus','设备状态',$product) !!}
					{!! $field->editFieldHTML('country','国家',$product) !!}
				</div>
				<div class="row">
					{!! $field->editFieldHTML('traffic','当前流量',$product) !!}
					{!! $field->editFieldHTML('memo','备注',$product) !!}			
				</div>

				
			</form>
		</div>
		<div class="tab-pane" id="logs" role="tabpanel">
			@include('partials.logs',['obj'=>$product])

		</div>
	</div>
		
	<div class="modal fade" id="selecttableModal" tabindex="-1" role="dialog" aria-labelledby="Selecttable">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      
	    </div>
	  </div>
	</div>

</div>
@endsection