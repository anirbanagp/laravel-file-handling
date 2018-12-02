<div class="footer_">
    <div class="copyright">
        <span>{{ $site_name }} © 2018.</span>
        <span>Powered by <a target="_blank" href="http://www.webgentechnologies.com">Webgen Technologies</a> | All Rights Reserved</span>
    </div>
</div>
</section>

    <!-- Jquery Core Js -->
    {{-- <script src="{{asset('new_admin/plugins/jquery/jquery.min.js')}}"></script> --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<script src="{{ asset("/admin/js/lightbox.min.js") }}" ></script>


    <script type="text/javascript">
        // loader main
        $(document).ready(function(){
            $(".loader").hide();
        });
    </script>
    <span id="asset_path" style="display:none">{{ asset('/') }}</span>
    <!-- Bootstrap Core Js -->
    <script src="{{asset('new_admin/plugins/bootstrap/js/bootstrap.js')}}"></script>
    <!-- <script src="{{asset('new_admin/plugins/bootstrap/js/dataTables.buttons.min.js')}}"></script> -->

    <!-- Select Plugin Js -->
    <script src="{{asset('new_admin/plugins/bootstrap-select/js/bootstrap-select.js')}}"></script>

    <!-- Slimscroll Plugin Js -->
    <script src="{{asset('new_admin/plugins/jquery-slimscroll/jquery.slimscroll.js')}}"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="{{asset('new_admin/plugins/node-waves/waves.js')}}"></script>

    <!-- Jquery CountTo Plugin Js -->
    <script src="{{asset('new_admin/plugins/jquery-countto/jquery.countTo.js')}}"></script>

    <!-- Morris Plugin Js -->
    <script src="{{asset('new_admin/plugins/raphael/raphael.min.js')}}"></script>
    <script src="{{asset('new_admin/plugins/morrisjs/morris.js')}}"></script>

    <!-- ChartJs -->
    <script src="{{asset('new_admin/plugins/chartjs/Chart.bundle.js')}}"></script>

    <!-- Flot Charts Plugin Js -->
    <script src="{{asset('new_admin/plugins/flot-charts/jquery.flot.js')}}"></script>
    <script src="{{asset('new_admin/plugins/flot-charts/jquery.flot.resize.js')}}"></script>
    <script src="{{asset('new_admin/plugins/flot-charts/jquery.flot.pie.js')}}"></script>
    <script src="{{asset('new_admin/plugins/flot-charts/jquery.flot.categories.js')}}"></script>
    <script src="{{asset('new_admin/plugins/flot-charts/jquery.flot.time.js')}}"></script>

    <!-- Sparkline Chart Plugin Js -->
    <script src="{{asset('new_admin/plugins/jquery-sparkline/jquery.sparkline.js')}}"></script>


<!-- Jquery DataTable Plugin Js -->
    <script src="{{asset('new_admin/plugins/jquery-datatable/jquery.dataTables.js')}}"></script>
    <script src="{{asset('new_admin/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js')}}"></script>
    <!-- <script src="{{asset('new_admin/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js')}}"></script> -->

	<script src="{{ asset("/admin/plugins/ckeditor/ckeditor.js") }}"></script>
	<!-- Moment Plugin Js -->
    <script src="{{ asset("/admin/plugins/momentjs/moment.js") }}"></script>
	<!-- Bootstrap Material Datetime Picker Css -->

    <link href="{{ asset("/admin/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css") }}" rel="stylesheet" />
	<script src="{{ asset("/admin/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js") }}"></script>

	<!-- Bootstrap Select Css -->
    <link href="{{ asset("/admin/plugins/bootstrap-select/css/bootstrap-select.css") }}" rel="stylesheet" />
	<!-- Select Plugin Js -->
    <script src="{{ asset("/admin/plugins/bootstrap-select/js/bootstrap-select.js") }}"></script>

	<!-- Multi Select Css -->
    <link href="{{ asset("/admin/plugins/multi-select/css/multi-select.css") }}" rel="stylesheet">
	<script src="{{ asset("/admin/plugins/multi-select/js/jquery.multi-select.js") }}"></script>



	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>



    <script type="text/javascript">
        $(function () {
            var table = $('.js-basic-example').DataTable({
                responsive: true
            });

        });
    </script>

    <!-- <script src="{{asset('new_admin/js/pages/index.js')}}"></script> -->

    <!-- Demo Js -->
    <script src="{{asset('new_admin/js/admin.js')}}"></script>
    <script src="{{asset('new_admin/js/tooltips-popovers.js')}}"></script>
    <script src="{{asset('new_admin/js/demo.js')}}"></script>

    <!-- amcharts -->
    <script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
    <script src="https://www.amcharts.com/lib/3/serial.js"></script>
    <script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
    <script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
    <!-- amcharts -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
    <!-- custom script -->
	<script src="{{asset('new_admin/js/custom.js')}}"></script>

    <script type="text/javascript">
        $("#leftsidebar").parent().addClass("left_panel_");

        //tooltips close on click
        /*$(".btn").click(function(){
            setTimeout(function(){
            $("[data-toggle='tooltip']").tooltip('destroy');
         }, 3000);
        });*/
        $('.btn').mouseenter(function(){
            var that = $(this)
            that.tooltip('show');
            setTimeout(function(){
                that.tooltip('hide');
            }, 2000);
        });

        $('.btn').mouseleave(function(){
            $(this).tooltip('hide');
        });



    </script>
    <!-- datatable -->
	<script type="text/javascript">
	$('.multiSelect_input').multiSelect();
	let dataTables =    $('.js-exportable').DataTable({
		@if(isset($show_export) && $show_export)

		dom: 'Bfrtip',
		responsive: false,
		buttons: [
			'copy', 'excel', 'print'
		]
		@endif
	});
	$(function () {
		if($('.ckeditor').length) {
			//CKEditor
			CKEDITOR.replace('ckeditor');
			CKEDITOR.config.height = 300;
		}
	});
    //Datetimepicker plugin
    $('.datetimepicker').bootstrapMaterialDatePicker({
        format: 'Y-MM-DD HH:mm',
        clearButton: true,
        weekStart: 1
    });

    $('.datepicker').bootstrapMaterialDatePicker({
        format: 'Y-MM-DD',
        clearButton: true,
        weekStart: 1,
        time: false
    });

    $('.timepicker').bootstrapMaterialDatePicker({
        format: 'HH:mm:ss',
        clearButton: true,
        date: false
    });
	// $.material.init();
	//Multi-select
	</script>
	<script type="text/javascript">
		$(document.body).on('click','.delete_button', function(event){
			event.preventDefault();
			var delete_url = $(this).attr('href');
			swal({
				  title: "Do you really want to delete ?",
				  type: "warning",
				  showCancelButton: true,
				  confirmButtonColor: "red",
				  confirmButtonText: "yes",
				  cancelButtonText: "cancel",
				  closeOnConfirm: false,
				  closeOnCancel: true
				},
				function(isConfirm){
				  if (isConfirm) {
						window.location.href = delete_url;
				  }
				}
			);
		});
		$(document.body).on('keyup', '#search_box', function() {
            $('#suggestion_status').show();
            $('#suggestion_status').attr('src',"{{asset('new_admin/images/danger.svg')}}");
			var keyword = $(this).val();
			if(keyword.length > 3) {
				$.ajax({
					url : '{{ route(isset($suggetion_url) ? $suggetion_url : 'admin-users-get-username' ).'/' }}'+keyword,
					success : function(data) {
						$(document.body).find('#suggestion-box').show();
						$(document.body).find('#suggestion-box').html(data);
					}
				});
			}
			if(keyword.length == 0) {
				$(document.body).find('#user_id').val('');
			}
		})
		$(window).click(function(){
			$(document.body).find('#suggestion-box').hide();
		});
		$(document.body).on('click', '.each-user', function() {
			var id	=	$(this).attr('data-id');
			var email	=	$(this).attr('data-val');
			$(document.body).find('#user_id').val(id);
            $(document.body).find('#user_id').change();
			$(document.body).find('#search_box').val(email);
            $('#suggestion_status').attr('src',"{{asset('new_admin/images/success.svg')}}");
		});
        function getAllParentList(id) {
          if(id) {
            $.ajax({
                 url: "{{route('admin-users-get-all-parents') .'/' }}" + id,
                 success: function(data) {
                     $("#parent_id").html(data).selectpicker('refresh');
                 }
            });
            } else {
              data = '<option value="">-- Please select --</option>';
              $("#parent_id").html(data).selectpicker('refresh');
            }
        };
		@if (isset($load_js) && !empty($load_js))
			{!! $load_js !!}
		@endif
	</script>
    @include('admin/support-ticket/support-ticket-scripts')
	@stack('pageScripts')
</body>
</html>
