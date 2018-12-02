@extends('admin.layout.adminlayout')
	@section('content')
		@include('admin.alert.form-validation-error')

        <div class="row clearfix">
            <form id="user-data" action="{{route('admin-support-ticket-management-add-ticket')}}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">Message</label>
                        <textarea name="message" cols="30" rows="5" class="form-control no-resize" value="" required>{{ old('message') }}</textarea>
                    </div>
                </div>
                
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">User</label>
                        <input type="text"  id="search_box" autocomplete="off"   class="form-control" placeholder="Enter User Email" value="">
                        <input type="hidden" name="user_id" id="user_id" class="form-control" >
                        <div class="sg_box" id="suggestion-box" style="display:none;"></div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">Allocate To</label>
                        <select name="allocate_to" id="parent_id" class="form-control show-tick selectpicker" data-live-search="true" tabindex="-98" required>
                            <option value="">-- Please select --</option>
                        </select>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">Ticket Department</label>
                        <select name="st_department_id" id="st_department_id" class="form-control show-tick" data-live-search="true" tabindex="-98" required>
                            <option value="">-- Please select --</option>
                            @foreach($department as $department_data)
                                <option value="{{ $department_data->id }}">{{ $department_data->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">Ticket Type</label>
                        <select name="st_type_id" id="st_type_id" class="form-control show-tick" data-live-search="true" tabindex="-98" required>
                            <option value="">-- Please select --</option>
                            @foreach($ticketType as $ticketType_data)
                                <option value="{{ $ticketType_data->id }}">{{ $ticketType_data->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">Ticket Priority</label>
                        <select name="st_priority_id" id="st_priority_id" class="form-control show-tick" data-live-search="true" tabindex="-98" required>
                            <option value="">-- Please select --</option>
                            @foreach($priority as $priority_data)
                                <option value="{{ $priority_data->id }}">{{ $priority_data->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">Ticket Status Type</label>
                        <select name="st_status_type_id" id="st_status_type_id" class="form-control show-tick" data-live-search="true" tabindex="-98" required>
                            <option value="">-- Please select --</option>
                            @foreach($statusType as $statusType_data)
                                <option value="{{ $statusType_data->id }}">{{ $statusType_data->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="user-info col-sm-6">
                    <div class="form-group">
                        <label class="form-label">File</label>
                        <div class="image">
                            <input name="file" type="file" value="{{ old('file') }}"/>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <button class="btn btn-primary waves-effect" type="submit">SUBMIT</button>
                </div>
            </form>
        </div>
@stop
@push('pageScripts')
<script type="text/javascript">
$('#user_id').change(function() {
    getAllParentList($(this).val());
})
</script>
@endpush
