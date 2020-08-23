<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{asset('images/logo.jpeg')}}">
    <link href="https://fonts.googleapis.com/css2?family=Changa:wght@800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>{{__('dashboard.payment')}}</title>
    @include('layouts.styles',['notifications' => []])
    <style>
        .custom-style{
            display: block;
            margin: 10px;
            width: 50vw;
            height: 60px;
            border-radius: 0.8rem;
            color: black;
            text-align: center;
            border:1px solid gold;
            box-shadow: 8px 8px 8px black;
            transition: transform 0.3s ease-in-out;
        }
        .custom-style:hover{
            transform: scale(1.03);
        }
        form div span{
            margin: 3px 10px;
            font-size: 20px;
            font-weight: 600;
            font-family: 'Courier';
        }
        form button:hover{
            cursor: pointer;
            background: gold;
            border-color: transparent;
            color: black;
            transform: scale(0.9);
        }
        form button{
            padding: 7px 30px;
            border-radius: 0.5rem;
            border: 1px solid gold;
            background: transparent;
            color: gold;
            font-weight: 700;
            font-size: 25px;
            letter-spacing: 2px;
            font-family: 'Courier';
            transition: transform 0.3s ease-in-out;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini" style="background-image: url('{{asset("/assets/img/mountain.jpg")}}')!important;
    background-size: cover; background-repeat: no-repeat; z-index: 50">


<div class="wrapper" style="padding-top: 3vh;">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="background-color: transparent!important;">
        <!-- Main content -->
        <section class="content container-fluid">
            <div style="font-family: 'Cortoba', Sans-Serif; color: gold; margin: 1vh 1rem;
                        -webkit-text-stroke: 1px black; font-size: 40px; text-shadow: 2px 2px 6px black;">
                * {{__('dashboard.payment')}}
            </div>

            <div class="container-fluid custom-div">
                <form method="post" action="{{route('payment.pay', $params['id'])}}">
                    @csrf
                    <input class="custom-style" type="text" placeholder="Card Number" name="card_number">
                    <input class="custom-style" type="text" placeholder="MM/YY" name="expiration_date">
                    <input class="custom-style" type="text" placeholder="CVV" name="cvv">
                    <input class="custom-style" type="text" placeholder="Card Holder Name" name="card_holder">
                    <button type="submit">{{__('dashboard.pay')}}</button>
                    <div @if(!isset($params['charge']))
                         style="display: none;"
                         @else
                         style="display: inline-block; color: gold;"
                        @endif>

                        @if(isset($params['charge']['status']))
                            @if($params['charge']['status'] == 'CAPTURED')
                                <span>{{__('dashboard.payment_success_with_number')}}
                                    {{$params['charge']['reference']['payment']}}</span>
                            @else
                                <span>paid status = {{$params['charge']['status']}}</span>
                            @endif
                        @else
                            <span>{{__('dashboard.payment_error')}}</span>
                        @endif
                    </div>
                </form>
            </div>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
</div>
@include('layouts.footer')

</body>
</html>



