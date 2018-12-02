@include('admin/layout/login_header')

<body class="login-page">
<div class="background_image" style="background: url('{{asset('new_admin/images/professional-business.jpg')}}'); background-size: cover;">

    <div class="login-box">
        <div class="card">
            <div class="body">
                <div class="brand">
                    <img src="{{ asset( 'storage/'.$site_logo ) }}" class="img-responsive center-block" />
                </div>
                <form id="reset_password" method="POST" action="{{route('admin-post-reset-password')}}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                @include('admin.alert.alert-message')
                    <div class="msg m-t-20">
                        Modify Your Password
                    </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Password" autocomplete="off" required="">
                        </div>

                        <div class="form-group">
                           <label>Confirm Password</label>
                           <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" autocomplete="off" required="">
                        </div>
                        <div class="clearfix">
                            <button class="btn bg-blue waves-effect" type="submit">RESET MY PASSWORD</button>
                            <a class="btn btn-info waves-effect" href="{{route('admin-login')}}">Log In!</a>
                        </div>
                </form>
            </div>
        </div>

@include('admin/layout/login_footer')
