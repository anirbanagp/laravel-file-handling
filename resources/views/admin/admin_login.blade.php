@include('admin/layout/login_header')

<body class="login-page">
<div class="background_image" style="background: url('{{asset('new_admin/images/professional-business.jpg')}}'); background-size: cover;">

    <div class="login-box">
        <div class="card">
            <div class="body">
                <div class="brand">
                    <img src="{{ asset( 'storage/'.$site_logo ) }}" class="img-responsive center-block" />
                </div>
                <form id="sign_in" method="POST" action="{{route('admin-post-login')}}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                @include('admin.alert.alert-message')

                    <div class="msg m-t-20">Log in to start your session</div>
                    <div class="clearfix">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Email Address" required="" aria-required="true" aria-invalid="true">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Password" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="">
                        <button class="btn btn-block bg-blue waves-effect" type="submit">LOG IN</button>
                    </div>

                    <div class=" m-t-20">
                        <a href="{{route('admin-get-forgot-password')}}">Forgot Password?</a>
                    </div>
                </form>
            </div>
        </div>

@include('admin/layout/login_footer')
