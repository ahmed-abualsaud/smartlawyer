@extends('layouts.master')
@section('title')
    {{__('dashboard.log')}}
@endsection
@section('styles')
    <style>
        #admins tbody tr {
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
                    <h3>{{__('dashboard.log')." ".$user->name}}</h3>
                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="log" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{__('dashboard.date')}}</th>
                                        <th>{{__('dashboard.from')}}</th>
                                        <th>{{__('dashboard.to')}}</th>
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

        var dataTable = $('#log').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            order: [[0, "asc"]],
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: [ 0, 1, 2 ]
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [ 0, 1, 2 ]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [ 0, 1, 2 ]
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [ 0, 1, 2 ]
                    }
                },

            ],
            select: true,
            ajax: {
                "url": '{{route("users.fetchLog",['id'=>$user->id])}}',
                "type": 'GET'
            },
            columns: [
                {data: 'date', name: 'date'},
                {data: 'from', name: 'from'},
                {data: 'to', name: 'to'},
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
    </script>
@endsection

