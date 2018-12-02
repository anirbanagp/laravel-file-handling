@extends('admin.layout.adminlayout')
	@section('content')
        <div class="panel-group pnl_collapse">
			<form class="company-module-form" action="{{ $post_url }}" method="POST">
				{!! csrf_field() !!}
			@forelse ($main_modules as $index => $each_main_module)
				@if($sub_modules->get($each_main_module->id))
					<div class="panel panel-default">
					  <div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" href="#collapse{{ $index }}">{{ $each_main_module->module }}</a>
							<div class="switch warning">
							   <label><input class="main_modules" data-index="{{ $index }}" name="{{ $each_main_module->id }}" value="1" {{ $each_main_module->status == 1 ? 'checked' : '' }} type="checkbox"><span class="lever"></span></label>
							</div>
						</h4>
					  </div>
					  <div id="collapse{{ $index }}" class="panel-collapse collapse">
						  <div class="panel-body">
								<ul class="list-group">
									@foreach ($sub_modules->get($each_main_module->id) as $sub_index => $each_sub_module)
										<li class="list-group-item">
										<div class="list-item-content">
										   <span>{{ $each_sub_module->module }}</span>
										   <div class="switch warning">
											  <label><input name="{{ $each_sub_module->id }}" class="sub_modules_of_{{ $each_main_module->id }}" value="1" {{ $each_sub_module->status == 1 ? 'checked' : '' }} type="checkbox"><span class="lever"></span></label>
										   </div>
									   </div>
									</li>
									@endforeach
							   </ul>
						  </div>
					  </div>
					</div>
				@else
					<div class="panel panel-default">
		                <div class="panel-heading">
		                  <h4 class="panel-title">
		                    <a data-toggle="">{{ $each_main_module->module }}</a>
		                    <div class="switch warning">
		                       <label><input name="{{ $each_main_module->id }}" value="1" {{ $each_main_module->status == 1 ? 'checked' : '' }} type="checkbox"><span class="lever"></span></label>
		                    </div>
		                  </h4>
		                </div>
		            </div>
				@endif
			@empty
				<div class="panel panel-default">
					<div class="panel-heading">
					  <h4 class="panel-title">
						<a data-toggle="">No Module Found..</a>
					  </h4>
					</div>
				</div>

			@endforelse
			@if($main_modules)
				<div class="text-center cntr_btn">
					<input type="submit" class="btn btn-primary waves-effect" value="Update" />
				</div>

			@endif
			</form>
        </div>
	@stop
	@push('pageScripts')
		<script type="text/javascript">
			$('.main_modules').click(function() {
				let id = $(this).attr('name');
				let index = $(this).attr('data-index');
				if ($(this).is(':checked')){
				    $('#collapse'+index).addClass('in');
					$('.sub_modules_of_'+id).prop('checked', true);
				} else {
					$('#collapse'+index).removeClass('in');
					$('.sub_modules_of_'+id).prop('checked', false);
				}
			})
		</script>
	@endpush
