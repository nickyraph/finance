@extends('layouts.dashboard.main')

@section('title')
    Rekodi mauzo - Mmiliki
@endsection

@section('content-title')
    Rekodi mauzo
@endsection

@section('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="row ">
        <div class="col-md-8 grid-margin stretch-card offset-md-2">
            <div class="card">
                <div class="card-body">
                    <form class="forms-sample" method="POST" action="{{route('owner.sales.store')}}">
                        @csrf
                        <div class="form-group">
                            <label for="item_id">Chagua Bidhaa</label>
                            <select class="form-control @error('item_id') is-invalid @enderror" id="item_id" name="item_id">
                                <option selected disabled>...</option>
                                @forelse($items as $item)
                                    <option value="{{$item->id}}" @if(old('item_id') == $item->id) selected @endif>{{$item->name}}</option>
                                @empty
                                    <option disabled>Hauna Bidhaa bado</option>
                                @endforelse
                            </select>
                            @error('item_id')
                            <span class="invalid-feedback" role="alert">
                               <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="customer">Mnunuzi</label>
                            <input type="text" class="form-control @error('customer') is-invalid @enderror" id="customer" name="customer" value="{{old('customer')}}">
                            @error('customer')
                            <span class="invalid-feedback" role="alert">
                               <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="item_id">Njia ya malipo</label>
                            <select class="form-control @error('payment_type') is-invalid @enderror" id="payment_type" name="payment_type">
                                <option selected disabled>...</option>
                                    <option value="Cash" @if(old('payment_type') == 'Cash') selected @endif>Fedha taslim</option>
                                    <option value="Credit" @if(old('payment_type') == 'Credit') selected @endif>Mkopo</option>
                                    <option value="Both" @if(old('payment_type') == 'Both') selected @endif>Fedha na Mkopo</option>
                            </select>
                            @error('payment_type')
                            <span class="invalid-feedback" role="alert">
                               <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-6">
                                    <label for="quantity" class="font-weight-bold">Idadi ya bidhaa Ulizouza</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="u_quantity">...</span>
                                        </div>
                                        <input type="number" class="form-control @error('unit_quantity') is-invalid @enderror" id="unit_quantity" name="unit_quantity" value="{{old('unit_quantity')}}">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="mini_q">...</span>
                                        </div>
                                        <input type="number" class="form-control @error('mini_unit_quantity') is-invalid @enderror" id="mini_unit_quantity" name="mini_unit_quantity" value="{{old('mini_unit_quantity')}}">

                                    </div>
                                    @error('unit_quantity')
                                    <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                     </span>
                                    @enderror
                                    @error('mini_unit_quantity')
                                    <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                     </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="quantity" class="font-weight-bold">Bei <small><i>(TZS)</i></small> <small class="smal"></small></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend" id="cash">
                                            <span class="input-group-text" >Taslim</span>
                                        </div>
                                        <input type="number" class="form-control @error('cash_amount') is-invalid @enderror" id="cash_amount" name="cash_amount" value="{{old('cash_amount')}}">
                                        <div class="input-group-prepend" id="credit">
                                            <span class="input-group-text" >Mkopo</span>
                                        </div>
                                        <input type="number" class="form-control @error('credit_amount') is-invalid @enderror" id="credit_amount" name="credit_amount" value="{{old('credit_amount')}}">
                                    </div>
                                    @error('cash_amount')
                                    <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                     </span>
                                    @enderror
                                    @error('credit_amount')
                                    <span class="invalid-feedback" role="alert">
                                      <strong>{{ $message }}</strong>
                                     </span>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Hifadhi bidhaa</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function (){
            $('#item_id').change(function (){
                if ($(this).val() != ''){
                    var id = $(this).val()
                    var _token = $('input[name="_token"]').val()
                    $.ajax({
                        url:"items/"+id,
                        method: "GET",
                        data: {id:id, _token:_token},
                        success:function (result){
                            $('#u_quantity').text(result.unit);
                            $('#mini_q').text(result.mini_unit);
                            $('#unit_quantity').change(function () {
                                $('.smal').text(result.unit_price * $(this).val() + result.mini_unit_price * $('#mini_unit_quantity').val())
                            })
                            $('#mini_unit_quantity').change(function () {
                                $('.smal').text(result.mini_unit_price * $(this).val() + result.unit_price * $('#unit_quantity').val())
                            })
                        }
                    })
                }
            });

            $('#payment_type').change(function () {
               var value = $(this).val()
                if (value != '') {
                    if (value === 'Cash') {
                        $('#credit').addClass('d-none');
                        $('#credit_amount').addClass('d-none');
                        $('#customer').removeAttr('required');
                        $('#cash').removeClass('d-none');
                        $('#cash_amount').removeClass('d-none');
                    } else if (value === 'Credit') {
                        $('#cash').addClass('d-none');
                        $('#cash_amount').addClass('d-none');
                        $('#customer').attr('required', 'true');
                        $('#credit').removeClass('d-none');
                        $('#credit_amount').removeClass('d-none');
                    } else if (value === 'Both') {
                        $('#cash').removeClass('d-none');
                        $('#cash_amount').removeClass('d-none');
                        $('#customer').attr('required', 'true');
                        $('#credit').removeClass('d-none');
                        $('#credit_amount').removeClass('d-none');
                    }
                }
            });
        });
    </script>
@endsection
