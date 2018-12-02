@include('admin/layout/header')
<!-- #User Info -->
@include('admin/layout/leftmenubar')
@include('admin/layout/legal')
</aside>
<!-- #END# Left Sidebar -->
@include('admin/layout/rightsidebar')
</section>

<section class="content">
<div class="container-fluid">

    @include('admin.alert.alert-message')

    @include('admin.alert.form-validation-error')


<div class="container-fluid">
    <div class="bs_tbl">
        <!-- Basic Validation -->
        <div class="card">
            <div class="header">
                <h2>Profile Settings</h2>
            </div>
            <div class="body input_main row">
                <form id="user-data" action="{{route('admin-profile-settings')}}" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                    <input type="hidden" name="id" value="{{ session('admin_details.id') }}"/>

                    <div class="col-md-6">
                        <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="full_name" value="<?php echo $user_details[0]->full_name; ?>" >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control" name="email" value="<?php echo $user_details[0]->email; ?>" >
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" value="<?php echo $user_details[0]->username; ?>" >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Mobile</label>
                            <input type="text" class="form-control" name="mobile" value="<?php echo $user_details[0]->mobile; ?>" >
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" value="" >
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="password_confirmation" value="" >
                        </div>
                    </div>

                   <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Profile Image</label>
                            <div class="image">
                                @if($user_details[0]->profile_image)
                                    <img width="48" height="48" src="{{ asset( 'storage/'.$user_details[0]->profile_image ) }}" alt="pf" />
                                @endif
                                <input name="profile_image" type="file" value="{{ old('profile_image') }}"/>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <button class="btn btn-primary waves-effect" type="submit">SUBMIT</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- #END# Basic Validation -->
    </div>

</div>
</section>

@include('admin/layout/footer')
