@extends('admin.layout.adminlayout')
	@section('content')
		@if($action['add'])
			<a href="{{ route('admin-users-add', $parent_id) }}" class="btn btn-success pull-right btn_top"><i class="material-icons">add_circle_outline</i>Add</a>
			<div class="devider"></div>
		@endif
		<div class="table-responsive">
			<table id="users-table" class=" crud-table table table-bordered table-striped table-hover dataTable">
				<thead>
					<tr>
						<th>Name</th>
						<th>Username</th>
						<th>Email</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
			</table>
		</div>
	@stop
	@push('pageScripts')
	<script type="text/javascript">
		$('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{!! route('admin-users-data', $parent_id)!!}',
        columns: [
            {data: 'full_name', name: 'full_name'},
            {data: 'username', name: 'username'},
            {data: 'email', name: 'email'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action'}
        ]
    });
	</script>
	@endpush
