<!-- Script for change allocate to in support ticket listing page - start -->
<script>
$(document.body).on('change',".allocate_to",function(event)
{
swal({
  title: "Are you sure?",
  type: "warning",
  showCancelButton: true,
  confirmButtonColor: "green",
  confirmButtonText: "Yes, Change it!",
  closeOnConfirm: true
},
function(){
    var id =$(event.target).attr('data-value');
    var allocate_to = $(event.target).val();
    if(allocate_to.length > 0){
        $.ajax({
         type: "POST",
         url: "{{route('admin-support-ticket-management-change-allocate-to')}}",
         headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
          data: {'id' : id, 'allocate_to' : allocate_to},
         success: function(data)
         {
            if(data == 1)
            {
                $('#msgDiv').show();
                setTimeout(function(){
                    $('#msgDiv').fadeOut('slow');
                },1000);
            }
         }
});
    }
});
});
</script>
<!-- Script for change allocate to in support ticket listing page - end -->

<!-- Script for change status in support ticket listing page - start -->
<script>
$(document.body).on('change',".change_status",function(event)
{
swal({
  title: "Are you sure?",
  type: "warning",
  showCancelButton: true,
  confirmButtonColor: "green",
  confirmButtonText: "Yes, Change it!",
  closeOnConfirm: true
},
function(){
    var id =$(event.target).attr('data-value');
    var status_id = $(event.target).val();
    if(status_id.length > 0){
        $.ajax({
         type: "POST",
         url: "{{route('admin-support-ticket-management-change-status')}}",
         headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
          data: {'id' : id, 'status_id' : status_id},
         success: function(data)
         {
            if(data == 1)
            {
                $('#msgDiv').show();
                setTimeout(function(){
                    $('#msgDiv').fadeOut('slow');
                },1000);
            }
         }
});
    }
});
});
</script>
<!-- Script for change status in support ticket listing page - end -->

<!-- Script for show single bet report details - start -->
<script type="text/javascript">
$(document.body).on('click',".view_details",function(){
var id = $(this).attr('data-id');
$('#viewBetModal-'+id).modal({
show: 'true'
});
});
</script>
<!-- Script for show single bet report details - end -->

<!-- Script for show ticket details in support ticket listing page - start -->
<script type="text/javascript">
$(document.body).on('click',".view_messeges",function(){
var id = $(this).attr('data-id');
$('#viewStModal').modal({
    show: 'true'
});
$('#show_support_message').html("<i class='fa fa-spinner fa-spin' style='font-size:36px;'></i> <B style='font-size:24px;'>Loading....</B>");
$(this).children('.badge').remove();
$.ajax({
         type: "POST",
         url: "{{route('admin-support-ticket-management-show-message')}}",
         headers: {
           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
          data: {'id' : id},
         success: function(result)
         {
            if(result)
            {
                $('#show_support_message').html(result);
            }
         }
});

});
</script>
<!-- Script for show ticket details in support ticket listing page - end -->

<!-- Script for change language - start -->
<script>
$('.change_language').click(function()
{
var lang = $(this).attr('data-lang');
$.ajax({
headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
},
type : 'POST',
data : {'lang':lang},
    url  : "{{ route('change-language') }}",
    success: function(data){
                window.location.reload();
    }
});
});
</script>
<!-- Script for change language - end -->

<!--####    Support Ticket unread check start    ####-->
{{-- <script type="text/javascript">
//$(document).ready(function(){
$.ajax({
    url: "{{route('admin-unread-tickets')}}",
    type:"GET",
    success(result){
        if (result > 0)
        {
            $('#unread_ticket_header').html(result);
            var msg = result+' unread ticket message';
            $('#unread_ticket_message').html(msg);
        }
        else
        {
            $('#unread_ticket_header').html('');
            var msg = 'No unread ticket messages';
            $('#unread_ticket_message').html(msg);
        }
    }
});

//});
</script> --}}
<!--####    Support Ticket unread check end    ####-->
