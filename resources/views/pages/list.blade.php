
@extends('main')
@section('content')

    <div class="page-header row no-gutters py-4">
        <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
            <h3 class="page-title">Pavedimų sąrašas </h3>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">


            <div class="row">
                <div class="col">
                    <div class="card card-small mb-4">
                        <div class="card-header border-bottom">
                            <h6 class="m-0">Pavedimai</h6>
                        </div>
                        <div class="card-body p-0 pb-3 text-center">
                            <h4 class="mb-0">{{ auth()->user()->name }} {{ auth()->user()->surname }}</h4>
                            <table class="table mb-0">
                                <thead class="bg-light">
                                <tr>
                                    <th scope="col" class="border-0">Nr.</th>
                                    <th scope="col" class="border-0">Data</th>
                                    <th scope="col" class="border-0">Siuntėjo sask id</th>
                                    <th scope="col" class="border-0">Gavėjo sask.id</th>
                                    <th scope="col" class="border-0">Mokėjimo paskirtis</th>
                                    <th scope="col" class="border-0">Suma</th>
                                    <th scope="col" class="border-0">Statusas</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $no=1 ?>
                                @forelse($transfers as $acc)
                                    <tr>
                                        <td>{{$no}}</td>
                                        <td>{{$acc->date}}</td>



                                        <td></br>{{$acc['account_id_from']}} </td>
                                        <td></br>{{$acc['account_id_to']}}</td>


                                        <td>{{$acc['purpose']}}</td>




                                            <td> {{$acc['amount']}}</td>






                                        <td>

                                            @if($acc['status']==1)
                                                Vykdomas. Atšaukti
                                            @else
                                                Įvykdytas
                                            @endif
                                        </td>
                                        <?php $no=$no+1 ?>
                                        @empty


                                        @endforelse


                                    </tr>


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>



@endsection

