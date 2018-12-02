@if ($errors->any())
<div class="alert alert-danger form_validation_error">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button>
    <ul class="error_list" style="list-style-type: none;padding: 0;margin: 0">
        @foreach ($errors->getMessages() as $error)
            <li><img src="{{asset('new_admin/images/danger.svg')}}" class="icon_svg" /> {{ $error[0] }}</li>
        @endforeach
    </ul>
</div>
@endif
