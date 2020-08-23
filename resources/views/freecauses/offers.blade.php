@extends('layouts.master')
@section('title')
    {{__('dashboard.offers')}}
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

                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="data" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{__('dashboard.lawyer')}}</th>
                                        <th>{{__('dashboard.avatar')}}</th>
                                        <th>{{__('dashboard.price')}}</th>
                                        <th>{{__('dashboard.description')}}</th>
                                        <th>{{__('dashboard.status')}}</th>
                                        <th>{{__('dashboard.lawyer_rate')}} / 5</th>
                                        <th>{{__('dashboard.user_rate')}} / 5</th>
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
                "url": '{{route("freecauses.fetchOffers",['id'=>$id])}}',
                "type": 'GET'
            },
            columns: [
                {data: 'lawyer_id', name: 'lawyer_id'},
                {data: 'lawyer_image', name: 'lawyer_image'},
                {data: 'price', name: 'price'},
                {data: 'description', name: 'description'},
                {data: 'status', name: 'status'},
                {data: 'lawyer_rate', name: 'lawyer_rate'},
                {data: 'user_rate', name: 'user_rate'},
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

        function deleteOffer(id) {
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
                        'url':'{{route('freecauses.deleteOffer')}}',
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

