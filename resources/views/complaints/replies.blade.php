@extends('layouts.master')
@section('title')
    {{__('dashboard.complaints_replies')}}
@endsection
@section('styles')
    <style>
        #data tbody tr {
            word-break: break-all;
        }
    </style>
@endsection
@section('modals')
@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="row">

                <div class="col-12">
                    <h3>{{__('dashboard.complaint_replies')." ".$complaint->number}}</h3>
                </div>
                <div class="col-12">
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="data" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{__('dashboard.reply_text')}}</th>
                                        <th>{{__('dashboard.reply_owner')}}</th>
                                        <th>{{__('dashboard.reply_role')}}</th>
                                        <th>{{__('dashboard.created_at')}}</th>
                                        <th>{{__('dashboard.action')}}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@section('scripts')
    <script type="text/javascript">

        var dataTable = $('#data').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            order: [[0, "asc"]],
            select: true,
            ajax: {
                "url": '{{route("complaints.fetchReplies",['id'=>$complaint->id])}}',
                "type": 'GET'
            },
            columns: [
                {data: 'reply_text', name: 'reply_text'},
                {data: 'user_id', name: 'user_id'},
                {data: 'role', name: 'role'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            aLengthMenu: [
                [10, 50, 100, 200, -1],
                [10, 50, 100, 200, "All"]
            ],
            "oLanguage": {
                "sSearch": "بحث : ",
                "oPaginate": {
                    "sFirst":    "الاول",
                    "sLast":    "الاخير",
                    "sNext":    "التالي",
                    "sPrevious": "السابق"
                },
                "sInfo": "اظهار _START_ الي _END_ من _TOTAL_ صف",
                "sLengthMenu": "اظهار _MENU_ صف بالصفحة الواحده",
                "sProcessing": "<img src='{{asset('loading.gif')}}' />",
            },

        });

        function deleteReply(id) {
            Swal.fire({
                title: 'هل انت متاكد من الحذف ؟',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم',
                cancelButtonText: 'ﻻ',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        'url':'{{route('complaints.deleteReply')}}',
                        'type':'Delete',
                        'data':{
                            'id' : id,
                            "_token": "{{ csrf_token() }}",
                        },
                        success:function(response){
                            Swal.fire(response.msg)
                            dataTable.ajax.reload();
                        },
                        error : function(response){
                            Swal.fire(response.msg)
                            dataTable.ajax.reload();
                        }
                    });
                }
            })

        }

    </script>
@endsection

