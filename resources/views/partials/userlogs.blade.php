<?php 
$logs=$obj->didManyLog()->latest('log_at')->with('belongsToUser')->paginate(20);
echo '<div class="pull-right">'.$logs->render().'</div>';
?>

<table class="table table-condensed table-hover">
	<thead>
		<tr>
			<th>时间</th>
			<th>用户</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		@foreach($logs as $log)
			<tr>
				<th scope="row">{{$log->log_at}}</th>
				<td>{{$log->belongsToUser->uid}}</td>
				<td>
					<?php
					$actions = $log->actions;
					$object = '<a href="'.url($log->object.'/'.$log->object_id.'/edit').'">'.$actions['object'].'</a>';
					$body=$actions['body'];
					$body=str_replace('{object}', $object, $body);
					unset($actions['body']);
					unset($actions['object']);
					foreach ($actions as $k => $v) {
						switch ($k) {
							case 'dirty':
								$replacement='';
								foreach ($v as $key => $value) {
									$replacement.=$key.':'.$value.', ';
								}
								//$replacement = json_encode($v,JSON_UNESCAPED_UNICODE);
								break;
							case 'products':
								$replacement = '';
								foreach ($v as $product) {
									$replacement.='<a href="'.url($k.'/'.$product['product_id'].'/edit').'">'.$product['pid'].'</a>';
								}
								//$replacement = json_encode($v,JSON_UNESCAPED_UNICODE);
								break;
							case 'order':
								$replacement = '<a href="'.url($k.'/'.$v['id'].'/edit').'">'.$v['text'].'</a>';
								break;
							default:
								$replacement = $v;
								break;
						}
						$body = str_replace('{'.$k.'}', $replacement, $body);
					}
					?>
				{!!$body!!}
				</td>	
			</tr>

		@endforeach
	</tbody>
</table>




