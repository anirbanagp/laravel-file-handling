<!-- Left Sidebar -->
<aside id="leftsidebar" class="sidebar">
    <!-- User Info -->
    <div class="user-info">
        <div class="image">
             <img class="center-block" width="48" height="48" src="{{ asset(Session::get('admin_details')['profile_image']) }}" alt="pf" />
        </div>
        <div class="info-container">
            <div class="name">
                    <strong class="text-center">Hi {{ Session::get('admin_details')['username'] }}</strong>
            </div>
        </div>
    </div>
    <!-- #User Info -->
    <!-- Menu -->
    <div class="menu custom">
        <ul class="list">
            <!-- <li class="header">MAIN NAVIGATION</li> -->
            <li class="@if(Route::current()->getName() == 'admin-dashboard') active  @endif">
                <a href="{{route('admin-dashboard')}}">
                    <img src="{{asset('new_admin/images/house.svg')}}" class="img-responsive" />
                    <span>Dashboard</span>
                </a>
            </li>
            @foreach($parent_menu as $parent_value)
            <li class="@if(starts_with(Route::current()->getAction()['as'],'admin-'.$parent_value->slug_name)) active  @endif">
                @if ($parent_value->is_group == 'no')
                    <a href="{{route('admin-'.$parent_value->slug_name)}}">
                        <!-- <i class="fa {{ $parent_value->icon }}"></i> -->
                        <img src="{{asset('new_admin/images/')}}/{{ $parent_value->icon }}" class="img-responsive" />
                        <span>{{ $parent_value->title }}</span>
                    </a>
                @else
                    <a href="javascript:void(0);" class="menu-toggle">
                        <!-- <i class="fa "></i> -->
                        <img src="{{asset('new_admin/images/')}}/{{ $parent_value->icon }}" class="img-responsive" />
                        <span>{{ $parent_value->title }}</span>
                    </a>
                    <ul class="ml-menu">
                    @if(isset($sub_menu[$parent_value->id][0]))
                     @foreach($sub_menu[$parent_value->id][0] as $value)
                     <li class="@if(str_is('admin-'.$parent_value->slug_name.'-'.str_replace_last('-list','',$value->slug_name).'*',Route::current()->getAction()['as']))) active  @endif" >
                         <a href="{{ route('admin-'.$parent_value->slug_name.'-'.$value->slug_name) }}">{{ $value->title }}</a>
                     </li>
                     @endforeach
                    @endif
             </ul>
                @endif
            </li>
            @endforeach
        </ul>
    </div>
    <!-- #Menu -->
</aside>
<!-- #END# Left Sidebar -->
