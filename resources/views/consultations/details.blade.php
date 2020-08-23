@extends('layouts.master')
@section('title')
    {{__('dashboard.consultation_details')}}
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
                            <h1>{{__('dashboard.consultation_details')}}</h1>
                            <div class="row">
                                <div class="col-md-4">
                                    <?php
                                    switch ($consultation->status){
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
                                    ?>
                                    <ul>
                                        <li><h4>{{__('dashboard.user')." : ".$consultation->user->name}}</h4></li>
                                        <li><h4>{{__('dashboard.cause_number')." : ".$consultation->number}}</h4></li>
                                        <li><h4>{{__('dashboard.title')." : ".$consultation->title}}</h4></li>
                                        <li><h4>{{__('dashboard.address')." : ".$consultation->address}}</h4></li>
                                        <li><h4>{{__('dashboard.details')." : ".$consultation->details}}</h4></li>
                                        <li><h4>{{__('dashboard.is_publish')." : "}}{{$consultation->is_publish == 1 ? __('dashboard.public') : __('dashboard.private')}}</h4></li>
                                        <li><h4>{{__('dashboard.status')." : "}}
                                                <span style="color: {{$color}}">{{$status}}</span>
                                            </h4></li>
                                    </ul>
                                </div>
                            </div>
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
                    <div style="margin-top: 20px;">
                        @if(auth()->user()->role == "office")
                            <a class="btn btn-info action-btn" onclick="sendMessage({{$consultation->id}})" title="{{__('dashboard.send_message')}}">
                            {{__('dashboard.send_message')}}</a>
                        @endif
                    </div>
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
        $('#messages').DataTable({
            destroy: true,
            processing: true,
            serverSide: true,
            order: [[3, "desc"]],
            select: true,
            ajax: {
                "url": '{{route("messages.fetchMessages",['type'=>'consultation','id'=>$consultation->id])}}',
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
    @include('message-form',['type'=>'consultation'])

@endsection

