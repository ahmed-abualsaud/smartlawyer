@extends('layouts.master')
@section('title')
    {{__('dashboard.causes')}}
@endsection
@section('styles')
    <style>
        #data tbody tr {
            word-break: break-all;
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
                                    <th>{{__('dashboard.title')}}</th>
                                    <th>{{__('dashboard.number')}}</th>
                                    <th>{{__('dashboard.judgment_date')}}</th>
                                    <th>{{__('dashboard.judgment_text')}}</th>
                                    <th>{{__('dashboard.court_name')}}</th>
                                    <th>{{__('dashboard.judicial_chamber')}}</th>
                                    <th>{{__('dashboard.consideration_text')}}</th>
                                    <th>{{__('dashboard.type')}}</th>
                                    <th>{{__('dashboard.is_public')}}</th>
                                    <th>{{__('dashboard.user')}}</th>
                                    <th>{{__('dashboard.status')}}</th>
                                    <th>{{__('dashboard.related_cause_number')}}</th>
                                    <th>{{__('dashboard.lawyer')}}</th>
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
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3,4,5,6,7,8,9,10,11,12]
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3,4,5,6,7,8,9,10,11,12]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3,4,5,6,7,8,9,10,11,12]
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [ 0, 1, 2, 3,4,5,6,7,8,9,10,11,12]
                    }
                },

            ],
            select: true,
            ajax: {
                "url": '{{route("causes.fetchCauses")}}',
                "type": 'GET'
            },
            columns: [
                {data: 'title', name: 'title'},
                {data: 'number', name: 'number'},
                {data: 'judgment_date', name: 'judgment_date'},
                {data: 'judgment_text', name: 'judgment_text'},
                {data: 'court_name', name: 'court_name'},
                {data: 'judicial_chamber', name: 'judicial_chamber'},
                {data: 'consideration_text', name: 'consideration_text'},
                {data: 'type', name: 'type'},
                {data: 'is_public', name: 'is_public'},
                {data: 'user_id', name: 'user_id'},
                {data: 'status', name: 'status'},
                {data: 'related_cause_number', name: 'related_cause_number'},
                {data: 'lawyer', name: 'lawyer'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            aLengthMenu: [
                [10, 50, 100, 200, -1],
                [10, 50, 100, 200, "All"]
            ],
            "oLanguage": {
                "sSearch": "بحث : "
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
                },
                {
                    "targets": 6,
                    "className": "text-center",
                },
                {
                    "targets": 7,
                    "className": "text-center",
                },
                {
                    "targets": 8,
                    "className": "text-center",
                },
                {
                    "targets": 9,
                    "className": "text-center",
                },
                {
                    "targets": 10,
                    "className": "text-center",
                },
                {
                    "targets": 11,
                    "className": "text-center",
                },
            ],
        });

        function deleteCause(id) {
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
                        'url':'{{route('causes.delete')}}',
                        'type':'Delete',
                        'data':{
                            'id' : id,
                            "_token": "{{ csrf_token() }}",
                        },
                        success:function(response){
                            Swal.fire(response.msg);
                            dataTable.ajax.reload();
                        },
                        error : function(response){
                            Swal.fire(response.msg);
                            dataTable.ajax.reload();
                        }
                    });
                }
            })

        }

    </script>
    @include('message-form',['type'=>'cause'])
@endsection
