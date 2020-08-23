@extends('layouts.master')
@section('title')
    {{__('dashboard.add_offer')}}
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
                    <h3 style="text-align: right">{{__('dashboard.add_offer')}}</h3>
                </div>
                <div class="col-12">

                    <div class="box">
                        <!-- /.box-header -->
                        <div class="box-body">
                            <form action="{{route('consultations.storeOffer')}}" method="post" enctype="multipart/form-data" data-parsley-validate>
                                @csrf
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="reply_text">{{__('dashboard.consultation_number')}}</label>
                                        <input type="text" class="form-control" readonly value="{{$consultation->number}}">
                                    </div>
                                    <input type="hidden" name="consultation_id" value="{{$consultation->id}}">
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="reply_text">{{__('dashboard.consultation_lawyer')}}</label>
                                        <select name="lawyer_id" id="" class="form-control" required>
                                            @foreach($lawyers as $lawyer)
                                                <option value="{{$lawyer->id}}" {{auth()->id() == $lawyer->id ? 'selected' : ''}}>{{$lawyer->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="reply_text">{{__('dashboard.price')}}</label>
                                        <input type="text" class="form-control" name="price" data-parsley-type="digits"	required>
                                        <strong style="color: red">{{__('dashboard.add_website_commission',['commission' => isset($settings->commission) ? $settings->commission : 0])}}</strong>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="reply_text">{{__('dashboard.description')}}</label>
                                        <textarea class="form-control" id="description" name="description"></textarea>
                                    </div>
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

