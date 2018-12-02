@include('admin/layout/login_header')

<body class="login-page">
<div class="background_image" style="background: url('{{asset('new_admin/images/professional-business.jpg')}}'); background-size: cover;">

    <div class="login-box">
        <div class="card">
            <div class="body">
                <div class="brand">
                    <img src="{{ asset( 'storage/'.$site_logo ) }}" class="img-responsive center-block" />
                </div>
                <form id="forgot_password" method="POST" action="{{route('admin-post-forgot-password')}}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                @include('admin.alert.alert-message')
                    <div class="msg m-t-20">
                        Enter your email address that you used to register. We'll send you an email with your username and a
                        link to reset your password.
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Email" required autofocus>
                    </div>
                    <div class="clearfix">
                        <button class="btn bg-blue waves-effect" type="submit">RESET MY PASSWORD</button>
                        <a class="btn btn-info waves-effect" href="{{route('admin-login')}}">Log In</a>
                    </div>
                </form>
            </div>
        </div>
@include('admin/layout/login_footer')
