@include('admin/layout/header')
<section>
@include('admin/layout/leftmenubar')
@include('admin/layout/rightsidebar')
</section>
<section class="content">
    <div class="brdcmb">
        <div class="brd_ovrly">
            <div class="brd_cnt">
                <div class="dsbrd_hd">
                    <h2>Dashboard</h2>
                    <h3>Welcome to <strong>{{ $site_name }}</strong></h3>
                </div>
                <div class="brdcmb_list">
                    <ul>
                        <li><a href="{{route('admin')}}" class="active"><i class="fa fa-home"></i> Home</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        @include('admin.alert.alert-message')
        <!-- Widgets -->
        <div class="row clearfix dbCount">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-blue hover-expand-effect custom">
                    <div class="icon">
                        <img src="{{asset('new_admin/images/user.svg')}}" class="img-responsive" />
                    </div>
                    <div class="content">
                        <div class="text">Users</div>
                        <div class="number count-to" data-from="0" data-to="2300" data-speed="1000" data-fresh-interval="20">2300</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-dark-yellow hover-expand-effect custom">
                    <div class="icon">
                        <img src="{{asset('new_admin/images/user.svg')}}" class="img-responsive">
                    </div>
                    <div class="content">
                        <div class="text">Online Users</div>
                        <div id="onlineUsers" class="number count-to" >0</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-violet hover-expand-effect custom">
                    <div class="icon">
                        <img src="{{asset('new_admin/images/support.svg')}}" class="img-responsive" />
                    </div>
                    <div class="content">
                        <div class="text">Support Ticket</div>
                        <div class="number count-to" data-from="0" data-to="2300" data-speed="1000" data-fresh-interval="20">2300</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="info-box bg-green hover-expand-effect custom">
                    <div class="icon">
                        <img src="{{asset('new_admin/images/credit-card.svg')}}" class="img-responsive" />
                    </div>
                    <div class="content">
                        <div class="text"><span>Transactions</span></div>
                        <div class="number count-to" data-from="0" data-to="2300" data-speed="1000" data-fresh-interval="20">2300</div>
                    </div>
                </div>
            </div>
        </div>
        <!-- #END# Widgets -->
        <div class="row charts_vs">
            <div class="col-md-6">
                <div id="visitors" class="chartdiv"></div>
            </div>
            <div class="col-md-6">
                <div id="transaction" class="chartdiv"></div>
            </div>
        </div>
    </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.1.1/socket.io.js"></script>
	{{-- <script>
	var socket = io("http://localhost:3000");
	socket.on("admin", function(message) {
	    $('#onlineUsers').html(message);
	});
	</script> --}}
@include('admin/layout/footer')
