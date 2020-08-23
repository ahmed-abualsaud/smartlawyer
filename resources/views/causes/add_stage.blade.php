@extends('layouts.master')
@section('title')
    {{__('dashboard.add_new_stage')}}
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
                    <h3 style="text-align: right">{{__('dashboard.add_new_stage')}}</h3>
                </div>
                <div class="col-12">

                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <form action="{{route('causes.storeNewStage')}}" method="post" enctype="multipart/form-data" data-parsley-validate>
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <h4>{{__('dashboard.previous_stage_details')}}</h4>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="cause_number">{{__('dashboard.cause_number')}}</label>
                                                <input type="text" class="form-control" name="related_cause_number" readonly value="{{$cause->number}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="old_judgment_text">{{__('dashboard.old_judgment_text')}}</label>
                                                <textarea type="text" class="form-control" name="old_judgment_text" required>{{old('old_judgment_text')}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-md-12">
                                        <div class="col-md-3">
                                            <h4>{{__('dashboard.next_stage_details')}}</h4>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="reply_text">{{__('dashboard.cause_lawyer')}}</label>
                                            <select name="lawyer_id" id="" class="form-control" required>
                                                @foreach($lawyers as $lawyer)
                                                    <option value="{{$lawyer->id}}" {{auth()->id() == $lawyer->id ? 'selected' : ''}}>{{$lawyer->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="number">{{__('dashboard.number')}}</label>
                                            <input type="text" class="form-control" name="number" value="{{old('number')}}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="title">{{__('dashboard.title')}}</label>
                                            <input type="text" class="form-control" name="title" {{old('title')}} required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="stage_name">{{__('dashboard.stage_name')}}</label>
                                            <input type="text" class="form-control" name="stage_name" {{old('stage_name')}} required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="court_name">{{__('dashboard.court_name')}}</label>
                                            <input type="text" class="form-control" name="court_name" {{old('court_name')}} required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="judicial_chamber">{{__('dashboard.judicial_chamber')}}</label>
                                            <input type="text" class="form-control" name="judicial_chamber" {{old('judicial_chamber')}}>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="judgment_date">{{__('dashboard.stage_date')}}</label>
                                            <input type="date" class="form-control" name="stage_date" {{old('stage_date')}} required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="consideration_text">{{__('dashboard.consideration_text')}}</label>
                                            <textarea type="text" class="form-control" name="consideration_text">{{old('consideration_text')}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="attachment">{{__('dashboard.attachment')}}</label>
                                            <input type="file" class="form-control" name="attachment">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-md btn-success" value="{{__('dashboard.save')}}">
                                        </div>
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

