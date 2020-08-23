@extends('layouts.master')
@section('title')
    {{__('dashboard.settings')}}
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
    <div class="content-wrapper" style="background-color: transparent;">
        <div style="font-family: 'Cortoba', Sans-Serif; color: gold; margin: 2vh 2vw;
                        -webkit-text-stroke: 1px black; font-size: 40px; text-shadow: 2px 2px 6px black;">
            * {{__('dashboard.settings')}}
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
                                        <th>{{__('dashboard.commission')}}</th>
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
            <hr>
            <div class="row">
                <div class="col-12">
                    <form action="{{route("settings.updateCase")}}" method="POST">
                        <div class="form-group">
                            <label for="casecount" style="font-family: 'Cortoba', Sans-Serif; color: gold; margin: 2vh 2vw;
                        -webkit-text-stroke: 1px black; font-size: 24px; text-shadow: 2px 2px 6px black; ">
                                * {{__('dashboard.nofcs')}} ::></label>
                            <input type="text" name="casecount" placeholder="{{__('dashboard.snofc')}}" value="{{ old('casecount') }}">
                            <button type="submit" style="cursor: pointer">{{__('dashboard.edit')}}</button>
                        </div>
                        @csrf
                    </form>
                    @if($admin['cascount'] != null)
                    @endif
                    <span style="color: gold; margin-right: 3vw;">{{__('dashboard.currentvalue')}} = {{$admin['casecount']}}</span>

                </div>
            </div>
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
                "url": '{{route("settings.fetchSettings")}}',
                "type": 'GET'
            },
            columns: [
                {data: 'commission', name: 'commission'},
                {data: 'action', name: 'action'},
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

        function updateSettings(){
            Swal.fire({
                title: "{{__('dashboard.update_settings')}}",
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: false,
                confirmButtonText: "{{__('dashboard.save')}}",
                showLoaderOnConfirm: true,
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        'url':'{{route("settings.updateSetting")}}',
                        'type':'POST',
                        'data':{
                            'commission' : result.value,
                            "_token": "{{ csrf_token() }}",
                        },
                        success:function(response){
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: "{{__('dashboard.updated_successfully')}}",
                                showConfirmButton: false,
                                timer: 1500
                            });
                            location.reload();
                        },
                        error : function(response){
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: "{{__('dashboard.failed_request')}}",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    });
                }
            })
        }
    </script>

@endsection

