@extends('admin.layout.adminlayout')
	@section('content')
		<div class="table-responsive">
			<table class="table table-bordered table-striped table-hover">
				<tbody>
					@forelse ($label_data as $key => $value)
						<tr>
							<td >{{ $value }}</td>
							<td >{!! $details[$key] !!}</td>
						</tr>
					@empty
					@endforelse()
				</tbody>
			</table>
			<div class="cntr_btn">
				@if (!empty($edit_url))
				<a href="{{ $edit_url }}" class="btn btn-info waves-effect">Edit</a>
				@endif
				@if (!empty($back_url))
				<a href="{{ $back_url }}" class="btn btn-primary waves-effect">Back to list</a>
				@endif
			</div>
		</div>
	@stop
