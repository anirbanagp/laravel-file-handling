@include('admin/layout/header')
<section class="left_panel_">
@include('admin/layout/leftmenubar')
@include('admin/layout/rightsidebar')
</section>

<section class="content">
	<div class="container-fluid">
		<div class="container-fluid">
			@include('admin.alert.alert-message')
			<div class="bs_tbl">
				<div class="card">
					<div class="header">
                        <h2>@if(isset($page_title)) {{ $page_title }} @endif</h2>
                    </div>
					<div class="body input_main">
						@yield('content')
					</div>
				</div>
			</div>
		</div>

</div>
@include('admin/layout/footer')
</section>
