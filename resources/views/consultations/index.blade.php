@extends('layouts.master')
@section('title')
    {{__('dashboard.consultations')}}
@endsection
@section('styles')
    <style>
        #data tbody tr {
            word-break: break-all;
            cursor: pointer;
        }

        #data tbody tr:hover {
            background-color: rgba(0,0,0,0.1);
        }
        #data .count::after {
            content: '';
            position: absolute;
            top: 97px;
            display: inline-block;
            width: 5px;
            height: 5px;
            border-radius: 100%;
            border: 2px solid;
            background-color: #ef5350;
        }
    </style>
@endsection
@section('modals')
@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="background-color: transparent;">
        <div style="font-family: 'Cortoba', Sans-Serif; color: gold; margin-top: 1vh;
                        -webkit-text-stroke: 1px black; font-size: 40px; text-shadow: 2px 2px 6px black;
                        margin-right: 2vw;">
            * {{__('dashboard.consultations')}}
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
                                    <th>{{__('dashboard.number')}}</th>
                                    <th>{{__('dashboard.title')}}</th>

                                    <th>{{__('dashboard.details')}}</th>
                                    <th>{{__('dashboard.is_publish')}}</th>
                                    <th>{{__('dashboard.user')}}</th>
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
                "url": '{{route("consultations.fetchConsultations")}}',
                "type": 'GET'
            },
            columns: [
                {data: 'number', name: 'number',render:function(data, type, row){
                        return "<a href='/consultations/details/"+ row.id +"'>" + row.id + "</a>"
                    }
                },
                {data: 'title', name: 'title'},
                {data: 'details', name: 'details'},
                {data: 'is_publish', name: 'is_publish'},
                {data: 'user_id', name: 'user_id'},
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

            'columnDefs': [
                {
                    "targets": 0,
                    "className": "text-center",
                },
                {
                    "targets": 1,
                    "className": "text-center",
                },
                {
                    "targets": 2,
                    "className": "text-center",
                },
                {
                    "targets": 3,
                    "className": "text-center",
                },
                {
                    "targets": 4,
                    "className": "text-center",
                },
                {
                    "targets": 5,
                    "className": "text-center",
                }
            ],
        });

        function deleteConsultation(id) {
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
                        'url':'{{route('consultations.delete')}}',
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
        $('#data').on( 'click', 'tbody tr', function (e) {
            if (!$(e.target).closest('.btn').length) {
                window.location.href = "/consultations/details/"+$(this).attr('id');
            }
        });
    </script>
    @include('message-form',['type'=>'consultation'])
@endsection

