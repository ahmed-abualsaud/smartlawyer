@extends('layouts.master')
@section('title')
    {{__('dashboard.causes')}}
@endsection
@section('styles')
    <style>
        .ifhover{
            height: 3rem;
            color: black;
            height: 10vh!important;
        }
        table{
            table-layout: fixed;
        }
        table td{
            text-align: center;
            overflow: hidden;
        }
        table th{
            text-align: center;
        }
        a:hover{
            color: black;
        }
    </style>
@endsection
@section('modals')
@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="background-color: transparent!important;">
        <!-- Main content -->
        <section class="content container-fluid">
            <div style="font-family: 'Cortoba', Sans-Serif; color: gold; margin-top: 1vh; margin-bottom: 2vh;
                        -webkit-text-stroke: 1px black; font-size: 40px; text-shadow: 2px 2px 6px black;">
                * {{__('dashboard.freecauses')}}
            </div>
            <div class="table-responsive">
                <div class="page-select" style="text-align: center;">
                    <ul>
                        <li>{{__('dashboard.shooo')}}</li>
                        <li><a href="{{route('freecauses', ['pages'=>10, 'i'=>0])}}">10</a></li>
                        <li><a href="{{route('freecauses', ['pages'=>50, 'i'=>0])}}">50</a></li>
                        <li><a href="{{route('freecauses', ['pages'=>100, 'i'=>0])}}">100</a></li>
                        <li><a href="{{route('freecauses', ['pages'=>200, 'i'=>0])}}">200</a></li>
                        <li><a href="{{route('freecauses', ['pages'=>10000000, 'i'=>0])}}">{{__('dashboard.all')}}</a></li>
                    </ul>
                </div>
                <table class="table table-bordered">
                    <tr class="thead-dark">
                        <th scope="col" width="5%">#</th>
                        <th scope="col" width="10%">{{__('dashboard.number')}}</th>
                        <th scope="col">{{__('dashboard.title')}}</th>

                        <th scope="col">{{__('dashboard.type')}}</th>
                        <th scope="col">{{__('dashboard.is_public')}}</th>
                        <th scope="col">{{__('dashboard.status')}}</th>
                        <th scope="col">{{__('dashboard.lawyer')}}</th>
                        <th scope="col" width="20%">{{__('dashboard.action')}}</th>
                    </tr>
                    @foreach($cases as $case)
                        <tr class="ifhover mt-10 bg-white">
                            <td class="align-middle">{{ $counter++ }}</td>
                            <td class="align-middle">{{ $case['number'] }}</td>
                            <td class="align-middle">{{ $case['title'] }}</td>


                            @if($case['type'] == 'new')
                                <td class="align-middle">{{ __('dashboard.new') }}</td>
                            @elseif($case['type'] == 'veto')
                                <td class="align-middle">{{ __('dashboard.veto') }}</td>
                            @elseif($case['type'] == 'stab')
                                <td class="align-middle">{{ __('dashboard.stab') }}</td>
                            @elseif($case['type'] == 'seek')
                                <td class="align-middle">{{ __('dashboard.seek') }}</td>
                            @endif

                            @if($case['is_public'] == 0)
                                <td class="align-middle">{{ __('dashboard.private') }}</td>
                            @elseif($case['is_public'] == 1)
                                <td class="align-middle">{{ __('dashboard.public') }}</td>
                            @endif

                            @if($case['status'] == 0)
                                <td class="align-middle">{{ __('dashboard.pending') }}</td>
                            @elseif($case['status'] == 1)
                                <td class="align-middle">{{ __('dashboard.inprogress') }}</td>
                            @elseif($case['status'] == 2)
                                <td class="align-middle">{{ __('dashboard.complete') }}</td>
                            @endif

                            @if($case['lawyer'] == null)
                                <td class="align-middle">{{ __('dashboard.nolawyer') }}</td>
                            @else
                                <td class="align-middle">{{ $case['lawyer'] }}</td>
                            @endif

                            <td>
                                <div style=" display: flex; justify-content: space-between;
                                     flex-wrap: wrap; width: 100%; padding: 0!important;">
                                    <a href="{{route('freecauses.details', $case['id'])}}">
                                        <i class="fa fa-info-circle fa-2x" aria-hidden="true" title="{{__('dashboard.casedetail')}}"></i>
                                    </a>
                                    <a class="btn action-btn" href="{{route('freecauses.offers', $case['id'])}}">
                                        <span  class="fa fa-handshake-o" aria-hidden="true" title="{{__('dashboard.offers')}}"></span>
                                    </a>
                                    @if(auth()->user()->role == 'office')
                                        @if($case['status'] == 0)
                                            <a class="btn action-btn" href="{{route('freecauses.addOffer', $case['id'])}}">
                                                <span class="fa fa-plus" title="{{__('dashboard.add_offer')}}"></span>
                                            </a>
                                        @endif
                                        @if(!is_null($case['related_cause_number']))
                                            <a class="btn action-btn" href="{{route('causes.addNewStage', $case['id'])}}">
                                                <span class="fa fa-rocket" aria-hidden="true" title="{{__('dashboard.add_stage')}}"></span>
                                            </a>
                                        @endif
                                        <a class="btn action-btn" onclick="sendMessage({!! $case['id'] !!})" title="{{__('dashboard.send_message')}}">
                                            <span class="fa fa-telegram" aria-hidden="true"></span>
                                        </a>
                                        <a class="btn action-btn '.$class.'" href="{{route('messages', ['tpe' => 'cause','id' =>$case['id']])}}">
                                            <span class="fa fa-envelope" title="{{__('dashboard.inbox')}}"></span>
                                        </a>
                                    @else
                                        <a class="btn action-btn" href="{{route('freecauses.delete',$case['id'])}}" title="{{__('dashboard.delete')}}">
                                            <span class="fa fa-close"></span>
                                        </a>
                                    @endif
                                    <a class="btn action-btn" href="{{route('freecauses.attachments', $case['id'])}}">
                                        <span class="fa fa-file" title="{{__('dashboard.attachments')}}"></span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </table>
                <ul class="paginate">
                    @for ($i = 0; $i < $numpages; $i++)
                        <li><a href="{{route('freecauses',['pages'=>$pages, 'i'=>$i])}}">{{$i+1}}</a></li>
                    @endfor
                </ul>

            </div>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

@endsection
@section('scripts')
    <script>
        window.onload = checkPage({!! $numpages !!});
        function checkPage(numpages)
        {
            if (numpages == 1)
            {
                $(".paginate").css({
                    "display": "none"
                });
            }
        }
    </script>
    @include('message-form',['type'=>'cause'])
@endsection
