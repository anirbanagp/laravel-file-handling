@if (session('alert_msg'))
    <div class="alert_cs alert alert-{{ session('alert_class') }} alert-dismissible">
        <img src="{{asset('new_admin/images/'.session('alert_class').'.svg')}}" class="icon_svg"/>
         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">x</span></button>
         <strong>{{ session('alert_msg') }}</strong>
    </div>
@endif
