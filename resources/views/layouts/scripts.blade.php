<!-- jQuery 3 -->
<script src="{{asset('assets/vendor_components/jquery/dist/jquery.min.js')}}"></script>
<!-- popper -->
<script src="{{asset('assets/vendor_components/popper/dist/popper.min.js')}}"></script>
<!-- Bootstrap 4.0-->
<script src="{{asset('assets/vendor_components/bootstrap/dist/js/bootstrap.js')}}"></script>
<!-- DataTables -->
<script src="{{asset('assets/vendor_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
<!-- Morris.js charts -->
<script src="{{asset('assets/vendor_components/raphael/raphael.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/morris.js/morris.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{asset('assets/vendor_components/jquery-sparkline/dist/jquery.sparkline.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('assets/vendor_components/moment/min/moment.min.js')}}"></script>
<script src="{{asset('assets/vendor_components/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
<!-- datepicker -->
<script src="{{asset('assets/vendor_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')}}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{asset('assets/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.js')}}"></script>
<!-- Slimscroll -->
<script src="{{asset('assets/vendor_components/jquery-slimscroll/jquery.slimscroll.js')}}"></script>
<!-- FastClick -->
<script src="{{asset('assets/vendor_components/fastclick/lib/fastclick.js')}}"></script>
<!-- peity -->
<script src="{{asset('assets/vendor_components/jquery.peity/jquery.peity.js')}}"></script>
<!-- easypiechart -->
<script type="text/javascript" src="{{asset('assets/vendor_components/easypiechart/dist/jquery.easypiechart.js')}}"></script>
<!-- Alfa_admin App -->
<script src="{{asset('js/template.js')}}"></script>

<!-- start - This is for export functionality only -->
<script src="{{asset('assets/vendor_plugins/DataTables-1.10.15/media/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/vendor_plugins/DataTables-1.10.15/extensions/Buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/vendor_plugins/DataTables-1.10.15/extensions/Buttons/js/buttons.flash.min.js')}}"></script>
<script src="{{asset('assets/vendor_plugins/DataTables-1.10.15/ex-js/jszip.min.js')}}"></script>
<script src="{{asset('assets/vendor_plugins/DataTables-1.10.15/ex-js/pdfmake.min.js')}}"></script>
<script src="{{asset('assets/vendor_plugins/DataTables-1.10.15/ex-js/vfs_fonts.js')}}"></script>
<script src="{{asset('assets/vendor_plugins/DataTables-1.10.15/extensions/Buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets/vendor_plugins/DataTables-1.10.15/extensions/Buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('assets/vendor_plugins/ckeditor/ckeditor.js')}}"></script>
<!-- end - This is for export functionality only -->
<!-- Alfa_admin for Data Table -->
<script src="{{asset('js/sweetalert2.min.js')}}"></script>

<script src="{{asset('/js/pages/data-table.js')}}"></script>
<!-- Parsly validation -->
<script src="{{asset('js/parsley.min.js')}}"></script>
{{--Toaster js--}}
<script src="{{asset('js/toastr.min.js')}}"></script>
{{--sweet alert--}}
<script src="{{asset('assets/vendor_components/select2/dist/js/select2.full.js')}}"></script>
<script src="{{asset('js/custom.js')}}"></script>
@toastr_js
@yield('scripts')
<script src="https://js.pusher.com/5.0/pusher.min.js"></script>
<script>
    var notificationsCount = "{{ count($notifications) }}";
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;
    var pusher = new Pusher( "{{ env('PUSHER_APP_KEY') }}" , {
        cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
        forceTLS: true
    });
    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
        // //Browser push notification
        notificationsCount = parseInt(notificationsCount) + 1;
        $('.notification_count').text(parseInt(notificationsCount));
        var link;
        @if(auth()->user()->role == "admin")
            link = "/complaints/replies/"+data.message.id;
        @else
            var authId = "{{auth()->id()}}";
        console.log(authId)
        console.log(data.message.lawyer_id)
            if(data.message.lawyer_id == authId){
                if(data.message.type == 0)
                    link = "/causes/offers/"+data.message.id;
                else
                    link = "/consultations/offers/"+data.message.id;
            }

        @endif
        $('#notification_count').after("<a href='"+link+"'><li style='text-align: right;margin-right: 10px;margin-top: 15px;border-bottom: 1px solid #f3f3f3;padding-bottom: 10px;'><i class=\"mdi mdi-bell text-aqua\" style='margin-left: 8px;'></i><strong>"+data.message.content+"</strong><p style='margin-right: 13px;'>"+data.message.date+"</p></li></a>");
    });
</script>

