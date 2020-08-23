@extends('layouts.master')
@section('title')
    {{__('dashboard.clients')}}
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
    <div class="content-wrapper" style="background-color: transparent">
        <div style="font-family: 'Cortoba', Sans-Serif; color: gold; margin-top: 1vh; margin-right: 2vw;
                        -webkit-text-stroke: 1px black; font-size: 40px; text-shadow: 2px 2px 6px black;">
            * {{__('dashboard.clients')}}
        </div>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">

                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="data" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{__('dashboard.name')}}</th>
                                        <th>{{__('dashboard.email')}}</th>
                                        <th>{{__('dashboard.phone')}}</th>
                                        <th>{{__('dashboard.national_id')}}</th>
                                        <th>{{__('dashboard.bio')}}</th>
                                        <th>{{__('dashboard.address')}}</th>
                                        <th>{{__('dashboard.avatar')}}</th>
                                        <th>{{__('dashboard.status')}}</th>
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
                "url": '{{route("users.fetchUsers")}}',
                "type": 'GET'
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'phone', name: 'phone'},
                {data: 'national_id', name: 'national_id'},
                {data: 'bio', name: 'bio'},
                {data: 'address', name: 'address'},
                {data: 'avatar', name: 'avatar'},
                {data: 'status', name: 'status'},
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

        function changeStatus(id,status) {
            var title = (status == 0) ? 'هل انت متاكد من تفعيل الحظر ؟' : 'هل انت متاكد من الغاء الحظر ؟';
            Swal.fire({
                title: title,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم',
                cancelButtonText: 'ﻻ',
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        'url':'{{route('users.changeStatus')}}',
                        'type':'POST',
                        'data':{
                            'id' : id,
                            'status' : status,
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

