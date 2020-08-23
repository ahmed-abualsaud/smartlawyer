@extends('layouts.master')
@section('title')
    {{__('dashboard.add_reply')}}
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
                    <h3 style="text-align: right">{{__('dashboard.add_reply')}}</h3>
                </div>
                <div class="col-12">

                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <form action="{{route('complaints.storeReply')}}" method="post" enctype="multipart/form-data" data-parsley-validate>
                                @csrf
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="reply_text">{{__('dashboard.complaint_number')}}</label>
                                        <input type="text" class="form-control" readonly value="{{$complaint->number}}">
                                    </div>
                                    <input type="hidden" name="complaint_id" value="{{$complaint->id}}">
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="reply_text">{{__('dashboard.reply')}}</label>
                                        <textarea class="form-control" id="reply_text" name="reply_text" required></textarea>
                                    </div>
                                    <input type="hidden" name="complaint_id" value="{{$complaint->id}}">
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-md btn-success" value="{{__('dashboard.save')}}">
                                    </div>
                                </div>
                            </form>
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
@endsection

