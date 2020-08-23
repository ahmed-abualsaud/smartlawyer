@extends('layouts.master')
@section('title')
    {{__('dashboard.paymentlist')}}
@endsection
@section('styles')
    <style>
        .ifhover{
            height: 3rem;
            color: black;
        }
        .ayhaga:hover{
            transform: scale(0.9);
            background-color: gold!important;
        }
    </style>
@endsection
@section('modals')
@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="background-color: transparent">
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid box-body">
                <table class="table table-bordered">
                    <tr class="thead-dark">
                        <th scope="col">#</th>
                        <th scope="col">{{__('dashboard.causenumber')}}</th>
                        <th scope="col">{{__('dashboard.username')}}</th>
                        <th scope="col">{{__('dashboard.lawyername')}}</th>
                        <th scope="col">{{__('dashboard.price')}}</th>
                        <th scope="col">{{__('dashboard.payment_id')}}</th>
                        <th scope="col">{{__('dashboard.payment_time')}}</th>

                    </tr>
                    @foreach($details as $detail)
                        <tr class="ifhover mt-10 bg-white">
                            <td class="align-middle" width="5%">{{ $counter++ }}</td>
                            <td class="align-middle" width="9%">{{ $detail['cause_number'] }}</td>
                            <td class="align-middle" width="5%">{{ $detail['user_name'] }}</td>
                            <td class="align-middle" width="9%">{{ $detail['lawyer_name'] }}</td>
                            <td class="align-middle" width="9%">{{ $detail['price'] }}</td>
                            <td class="align-middle" width="9%">{{ $detail['payment_id'] }}</td>
                            <td class="align-middle" width="9%">{{ $detail['payment_time'] }}</td>
                        </tr>
                    @endforeach
                </table>

                <a class="ayhaga" href="{{route('showPaymentsToAdmin' )}}" style="display: inline-block; padding: 12px 42px; background-color: white;
                          margin-top: 15px; cursor: pointer; border-radius: 0.4rem; box-shadow: 5px 5px 8px black;
                          transition: transform 0.3s ease-in-out; font-weight: 600;">
                    {{__('dashboard.goback')}}
                </a>
            </div>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

@endsection

