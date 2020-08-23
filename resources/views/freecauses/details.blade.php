@extends('layouts.master')
@section('title')
    {{__('dashboard.cause_details')}} {{__('dashboard.cause_number')." : ".$cause->number}}
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
                            <h1>{{__('dashboard.cause_details')}}</h1>
                            <div class="row">
                                <div class="col-md-4">
                                    <?php
                                    if (isset($cause)) {
                                        switch ($cause->status){
                                            case 1:
                                                $status = __('dashboard.in_progress');
                                                $color = 'blue';
                                                break;
                                            case 2:
                                                $status = __('dashboard.complete');
                                                $color = 'green';
                                                break;
                                            default :
                                                $status = __('dashboard.pending');
                                                $color = 'red';
                                                break;
                                        }
                                    }
                                    ?>
                                    <ul>
                                        <li><h4>{{__('dashboard.user')." : ".$cause->user->name}}</h4></li>
                                        <li><h4>{{__('dashboard.cause_number')." : ".$cause->number}}</h4></li>
                                        <li><h4>{{__('dashboard.title')." : ".$cause->title}}</h4></li>
                                        <li><h4>{{__('dashboard.court_name')." : ".$cause->court_name}}</h4></li>
                                        <li><h4>{{__('dashboard.judgment_date')." : ".$cause->judgment_date}}</h4></li>
                                        <li><h4>{{__('dashboard.judgment_text')." : ".$cause->judgment_text}}</h4></li>
                                        <li><h4>{{__('dashboard.judicial_chamber')." : ".$cause->judicial_chamber}}</h4></li>
                                        <li><h4>{{__('dashboard.consideration_text')." : ".$cause->consideration_text}}</h4></li>
                                        <li><h4>{{__('dashboard.type')." : ".$cause->type}}</h4></li>
                                        <li><h4>{{__('dashboard.publish_type')." : "}}{{$cause->is_public == 1 ? __('dashboard.public') : __('dashboard.private')}}</h4></li>
                                        <li><h4>{{__('dashboard.status')." : "}}
                                            <span style="color: {{$color}}">{{$status}}</span>
                                            </h4></li>
                                    </ul>
                                </div>
                            </div>
                            <br>
                            <h3>{{__('dashboard.attachments')}}</h3>
                            <table id="data" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>{{__('dashboard.id')}}</th>
                                    <th>{{__('dashboard.action')}}</th>
                                </tr>
                                </thead>
                            </table>
                            <br>
                            <h3>{{__('dashboard.messages')}}</h3>

                            <table id="messages" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>{{__('dashboard.from')}}</th>
                                    <th>{{__('dashboard.to')}}</th>
                                    <th>{{__('dashboard.message')}}</th>
                                    <th>{{__('dashboard.date')}}</th>
                                </tr>
                                </thead>
                            </table>

                        </div>
                        <!-- /.box-body -->

                    </div>
                    <!-- /.box -->
                    @if(auth()->user()->role == "office")
                        <div style="margin-top: 20px;">
                            <a class="btn btn-info action-btn" onclick="sendMessage({{$cause->id}})" title="{{__('dashboard.send_message')}}">
                                {{__('dashboard.send_message')}}</a>
                        </div>
                    @endif
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
                "url": '{{route("freecauses.fetchAttachments",['id'=>$cause->id])}}',
                "type": 'GET'
            },
            columns: [
                {data: 'id', name: 'id'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            aLengthMenu: [
                [10, 50, 100, 200, -1],
                [10, 50, 100, 200, "All"]
            ],
            "oLanguage": {
                "sSearch": "بحث : "
            }
        });
        function deleteAttachment(id) {
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
                        'url':'{{route('freecauses.deleteAttachment')}}',
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

        $('#messages').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            order: [[3, "desc"]],
            select: true,
            ajax: {
                "url": '{{route("messages.fetchMessages",['type'=>'cause','id'=>$cause->id])}}',
                "type": 'GET'
            },
            columns: [
                {data: 'from', name: 'from'},
                {data: 'to', name: 'to'},
                {data: 'message', name: 'message'},
                {data: 'created_at', name: 'created_at'},
            ],
            aLengthMenu: [
                [10, 50, 100, 200, -1],
                [10, 50, 100, 200, "All"]
            ],
            "oLanguage": {
                "sSearch": "بحث : "
            }
        });
    </script>
    @include('message-form',['type'=>'cause'])
@endsection

