@extends('main')
@section('content')

    <div class="page-header row no-gutters py-4">
        <div class="col-12 col-sm-4 text-center text-sm-left mb-0">

            <h3 class="page-title">Profilis</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            @if (\Auth::user() == true)
            <div class="card card-small mb-4 pt-3">
                <div class="card-header border-bottom text-center">




                        <h4 class="mb-0">{{ auth()->user()->name }} {{ auth()->user()->surname }}</h4>




                </div>
                <ul class="list-group list-group-flush">

                    <li class="list-group-item p-4 col-lg-6">

                        <strong class="text-muted d-block mb-2">{{$accounts}}</strong>
                        @foreach($accounts as $account)
                           {{$account->id}}
                        @endforeach
                    </li>
                    <li class="list-group-item p-4 col-lg-6">
                        <?php $rez=DB::Table('accounts')->select('id', 'balance', 'main_account')->where('user_id', auth()->user()->id)->get()?>
                    @foreach($rez as $r)
                        <strong class="text-muted d-block mb-2">Sąskaita:   <span>{{$r->id}}</span> </strong>
                        <strong class="text-muted d-block mb-2">Saskaita yra:   <span>{{$r->main_account}}</span> </strong>
                        <strong class=" list-group-item text-muted d-block mb-2">Balansas:   <span>{{$r->balance}}</span> </strong>
                        @endforeach
                    </li>
                    <li class="list-group-item p-4">

                    </li>
                </ul>
            </div>
            @endif
        </div>


    </div>

@endsection
