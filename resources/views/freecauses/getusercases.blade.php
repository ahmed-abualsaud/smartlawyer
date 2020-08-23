@extends('layouts.master')
@section('title')
    {{__('dashboard.freecauses')}}
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
                        <th scope="col">{{__('dashboard.number')}}</th>
                        <th scope="col">{{__('dashboard.title')}}</th>
                        <th scope="col">{{__('dashboard.judgment_date')}}</th>
                        <th scope="col">{{__('dashboard.judgment_text')}}</th>
                        <th scope="col">{{__('dashboard.court_name')}}</th>
                        <th scope="col">{{__('dashboard.judicial_chamber')}}</th>
                        <th scope="col">{{__('dashboard.consideration_text')}}</th>
                        <th scope="col">{{__('dashboard.type')}}</th>
                        <th scope="col">{{__('dashboard.is_public')}}</th>
                        <th scope="col">{{__('dashboard.status')}}</th>
                        <th scope="col">{{__('dashboard.lawyer')}}</th>
                    </tr>
                    @foreach($cases as $case)
                        <tr class="ifhover mt-10 bg-white">
                            <td class="align-middle" width="5%">{{ $counter++ }}</td>
                            <td class="align-middle" width="5%">{{ $case['number'] }}</td>
                            <td class="align-middle" width="9%">{{ $case['title'] }}</td>
                            <td class="align-middle" width="9%">{{ $case['judgment_date'] }}</td>
                            <td class="align-middle" width="9%">{{ $case['judgment_text'] }}</td>
                            <td class="align-middle" width="9%">{{ $case['court_name'] }}</td>
                            <td class="align-middle" width="9%">{{ $case['judicial_chamber'] }}</td>
                            <td class="align-middle" width="9%">{{ $case['consideration_text'] }}</td>

                            @if($case['type'] == 'new')
                                <td class="align-middle" width="9%">{{ __('dashboard.new') }}</td>
                            @elseif($case['type'] == 'veto')
                                <td class="align-middle" width="9%">{{ __('dashboard.veto') }}</td>
                            @elseif($case['type'] == 'stab')
                                <td class="align-middle" width="9%">{{ __('dashboard.stab') }}</td>
                            @elseif($case['type'] == 'seek')
                                <td class="align-middle" width="9%">{{ __('dashboard.seek') }}</td>
                            @endif

                            @if($case['is_public'] == 0)
                                <td class="align-middle" width="9%">{{ __('dashboard.private') }}</td>
                            @elseif($case['is_public'] == 1)
                                <td class="align-middle" width="9%">{{ __('dashboard.public') }}</td>
                            @endif

                            @if($case['status'] == 0)
                                <td class="align-middle" width="9%">{{ __('dashboard.pending') }}</td>
                            @elseif($case['status'] == 1)
                                <td class="align-middle" width="9%">{{ __('dashboard.inprogress') }}</td>
                            @elseif($case['status'] == 2)
                                <td class="align-middle" width="9%">{{ __('dashboard.complete') }}</td>
                            @endif

                            @if($case['lawyer'] == null)
                                <td class="align-middle" width="9%">{{ __('dashboard.nolawyer') }}</td>
                            @else
                                <td class="align-middle" width="9%">{{ $case['lawyer'] }}</td>
                            @endif
                        </tr>
                    @endforeach
                </table>

                <a class="ayhaga" href="{{route('showCasesToAdmin' )}}" style="display: inline-block; padding: 12px 42px; background-color: white;
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

