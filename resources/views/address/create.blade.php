@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ isset($address) ? 'Edit' :  'Create New'}} Address
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{isset($address) ? route('address.update',['address' => $address->id]) : route('address.store')}}">

                            @csrf

                            @if( isset($address) )
                                {{method_field('PATCH')}}
                            @endif

                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea rows="5" id="address" class="form-control" name="address">{{ isset($address) ?  old('address',$address->address ) : old('address')}}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="postal-code">Postal Code</label>
                                <input type="text" id="postal-code" class="form-control" name="postal_code" value="{{ isset($address) ?  old('postal_code',$address->postal_code ) : old('postal_code')}}">
                            </div>
                            <div class="form-group">
                                <label for="state">State</label>
                                <select id="state" name="state" class="form-control">
                                    <option>-- select state --</option>
                                    @foreach($state as $states)
                                        <option value="{{$states->id}}" @if(old('state') == $states->id) selected @elseif(isset($address) && $address->state_id == $states->id) selected @endif>{{$states->state_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="city">City</label>
                                <select id="city" name="city" class="form-control">
                                    <option value="">-- select city --</option>

                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>



            var html = `<option value="">-- select city --</option>`;

            var city = document.getElementById('city');

            $(document).ready(function (){

                getCity($("#state").val());


            });

            $('#state').on('change', function (){

                getCity($(this).val())


            });

            function getCity(state){
                $.ajax({
                    type:'POST',
                    url:`{{route('city')}}` ,
                    data: {
                        '_token' : $('meta[name="csrf-token"]').attr('content'),
                        'state_id': state,

                    },
                    success:function(data) {


                        city.innerHTML = html;

                        data.cities.map((value)=>{
                            appendCity(value);
                        })





                    },
                    error:function(data){

                        city.innerHTML =html;

                    }
                });
            }

            function appendCity(value){
                var option = document.createElement("option");
                option.text = value.cities_name;
                option.value = value.id;

                if(`{{isset($address) ? $address->city_id : ''}}` == value.id){
                    option.selected = true;
                }

                city.appendChild(option);
            }
    </script>

@endsection
