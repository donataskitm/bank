@extends('main')
@section('content')


    <div class="page-header row no-gutters py-4">
        <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
            <h3 class="page-title">Pavedimų paieška</h3>
        </div>
    </div>
    <div class="row">

        <div class="col-lg-12">
            <div class="card card-small mb-4">
                <div class="card-header border-bottom">
                    <h6 class="m-0">Pavedimo duomenys</h6>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item p-3">
                        <div class="row">
                            <div class="col">
                                <form   action="/list" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="faccount">Sąskaitos numeris</label>
                                            <select id="faccount" name="faccount" class="form-control">
                                                <option selected>Pasirinkite...</option>
                                 @foreach(DB::Table('accounts')->select( 'account_no')->where('user_id', auth()->user()->id)->get() as $rez)
                                                    <option>{{$rez->account_no}}</option>
                        @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="datefrom">Periodo pradžia</label>
                                            <input type="text" class="date form-control" id="datefrom" name="datefrom" placeholder="First Name" value=" "> </div>

                                        <div class="form-group col-md-6">
                                            <label for="fdateto">Periodo pabaiga</label>
                                            <input type="text" class="date form-control" id="dateto" name="dateto" placeholder="Last Name" value=" "> </div>
                                    </div>

                                    <button type="submit" class="btn btn-accent">Ieškoti</button>
                                </form>

                            </div>
                        </div>
                    </li>
                </ul>
            </div>



        </div>
    </div>






    <script type="text/javascript">
        $('.date').datepicker({
            format: 'yyyy-mm-dd'
        });
    </script>





@endsection

