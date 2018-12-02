@extends('admin.layout.adminlayout')
@section('content')

<div class="table-responsive">
	<table id="log-table" class=" crud-table table table-bordered table-striped table-hover dataTable">
		<thead>
			<tr>
				<th>Date</th>
				<th>Username</th>
				<th>Activity</th>
				{{--
				<th>Action</th> --}}
			</tr>
		</thead>
	</table>
</div>
@stop
@push('pageScripts')
<script type="text/javascript">
	$('#log-table').DataTable({
		processing: true,
		serverSide: true,
		ajax: '{!! route('
		admin - user - activity - log - data ', $parent_id)!!}',
		columns: [{
				data: 'created_at',
				name: 'created_at'
			},
			{
				data: 'user_id',
				name: 'user_id'
			},
			{
				data: 'event',
				name: 'event'
			},
			// {data: 'action', name: 'action'},
		],
	});
</script>
@endpush
