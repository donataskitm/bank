@extends('main')
@section('content')

    <div class="page-header row no-gutters py-4">
        <div class="col-12 col-sm-4 text-center text-sm-left mb-0">

            <h3 class="page-title">Profilis</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">

                <div class="card card-small mb-4 pt-3">
                    <div class="card-header border-bottom text-center">




                        <h4 class="mb-0">{{ auth()->user()->name }} {{ auth()->user()->surname }}</h4>

                    </div>
                    <ul class="list-group list-group-flush">


                        @foreach($accounts as $account)
                        <li class="list-group-item p-4 col-lg-6">

                                <strong class="text-muted d-block mb-2">Sąskaita:   <span>{{$account->account_no}}</span> </strong>
                                <strong class="text-muted d-block mb-2">Saskaita yra:   <span>

                                         @if(($account->main_account)==1)
                                            Pagrindinė sąskaita
                                        @else
                                            Kortelės sąskaita
                                        @endif

                                        </span> </strong>
                                <strong class=" list-group-item text-muted d-block mb-2">Balansas:   <span>{{$account->balance}}</span> </strong>

                        </li>@endforeach

                    </ul>
                </div>

        </div>


    </div>

@endsection
