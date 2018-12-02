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
				@if ($view_full_details_link)
				<a href="javascript:void(0);" data-code-url="{{ route("admin-subscription-management-companies-send-code-to-verify", ['view']).'/' }}" data-redirect-url="{{ route("admin-subscription-management-companies-view-full-details").'/' }}" data-id="{{ $id }}" class="btn btn-warning access_all_details waves-effect">View Full Details</a>
				@endif
				@if ($edit_full_details_link)
				<a href="javascript:void(0);" data-code-url="{{ route("admin-subscription-management-companies-send-code-to-verify", ['edit']).'/' }}" data-redirect-url="{{ route("admin-subscription-management-companies-edit-full-details").'/' }}" data-id="{{ $id }}" class="btn bg-teal access_all_details waves-effect">Edit Full Details</a>
				@endif
				@if (!empty($edit_url))
				<a href="{{ $edit_url }}" class="btn btn-info waves-effect">Edit</a>
				@endif
				@if (!empty($back_url))
				<a href="{{ $back_url }}" class="btn btn-primary waves-effect">Back to list</a>
				@endif
			</div>
		</div>
	@stop
    @push('pageScripts')
        <script type="text/javascript">
			let code_url = '';
			let redirect_url = '';
            $('.access_all_details').click(function() {
                var id = $(this).attr('data-id');
				code_url = $(this).attr('data-code-url');
				redirect_url = $(this).attr('data-redirect-url');
                $.confirm({
                    theme:'material',
            		closeIcon: true,
            		draggable: false,
            		closeIconClass: 'fa fa-close',
                    icon: 'fa fa-warning',
                    type  : 'blue',
                    title: 'Confirm!',
                    content: 'Are you sure? We will send you a code to verify.',
                    buttons: {
                        confirm: function () {
							let info = $.alert('we are sending an email. Please wait..');
                            $.ajax({
                                url : code_url + id,
                                success : function(data) {
                                    if(data == 1) {
										info.close();
                                        showAccessTokenPopUp();
                                    }
                                }
                            });
                        },
                        cancel: function () {
                            //nothing
                        },
                    }
                });
            });
            function showAccessTokenPopUp() {
                $.confirm({
                    title: 'Verify yourself!',
                    content: '' +
                    '<form action="" class="formName">' +
                    '<div class="form-group">' +
                    '<label>Enter the code received in registered email </label>' +
                    '<input type="text" placeholder="Your code" class="code form-control" required />' +
                    '</div>' +
                    '</form>',
                    buttons: {
                        formSubmit: {
                            text: 'Submit',
                            btnClass: 'btn-blue',
                            action: function () {
                                var code = this.$content.find('.code').val();
                                if(!code){
                                    $.alert('provide a valid token');
                                    return false;
                                }
                                window.location.href = redirect_url + code;
                            }
                        },
                        cancel: function () {
                            //close
                        },
                    },
	            });
	        }
        </script>
    @endpush
