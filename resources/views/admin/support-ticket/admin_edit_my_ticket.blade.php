@extends('admin.layout.adminlayout')
    @section('content')
        @include('admin.alert.form-validation-error')

         <div class="row">
            <form id="user-data" action="{{route('admin-support-ticket-management-edit-my-ticket')}}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <input type="hidden" name="id" value="{{ $ticketData->id }}"/>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">Ticket Number</label>
                        <input type="text" class="form-control" name="ticket_number" value="{{ $ticketData->ticket_number }}" readonly required>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" value="{{ $ticketData->title }}" readonly required>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">Message</label>
                        <textarea name="message" cols="30" rows="5" class="form-control no-resize" readonly required>{{ $ticketData->message }}</textarea>
                    </div>
                </div>                   

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">Player</label>
                        <select name="player_id" id="player_id" class="form-control show-tick selectpicker" data-live-search="true" tabindex="-98" disabled>
                              @foreach($player as $player_data)
                              <option value="{{ $player_data->id }}" selected  >{{ $player_data->username }}</option>
                              @endforeach
                        </select>
                      </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">Allocate To</label>
                        <select name="allocate_to" id="allocate_to" class="form-control show-tick selectpicker" data-live-search="true" tabindex="-98" required>
                            <option value="">-- Please select --</option>
                            @foreach($allUsers as $allUsers_data)
                            <option value="{{ $allUsers_data['id'] }}" @if($allUsers_data['id']==$ticketData->allocate_to) selected @endif >{{ $allUsers_data['username'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="form-label">Ticket Department</label>
                        <select name="st_department_id" id="st_department_id" class="form-control show-tick" data-live-search="true" tabindex="-98" required>
                            <option value="">-- Please select --</option>
                            @foreach($department as $department_data)
                                <option value="{{ $department_data->id }}" @if($department_data->id==$ticketData->st_department_id) selected @endif >{{ $department_data->name }}</option>
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
                                <option value="{{ $ticketType_data->id }}" @if($ticketType_data->id==$ticketData->st_type_id) selected @endif >{{ $ticketType_data->name }}</option>
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
                                <option value="{{ $priority_data->id }}" @if($priority_data->id==$ticketData->st_priority_id) selected @endif >{{ $priority_data->name }}</option>
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
                                <option value="{{ $statusType_data->id }}" @if($statusType_data->id==$ticketData->st_status_type_id) selected @endif >{{ $statusType_data->name }}</option>
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
