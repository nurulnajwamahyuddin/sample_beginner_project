@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ __('Dashboard') }}
                    <a href="{{route('address.create')}}" class="btn btn-outline-primary">Create New Address</a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                        @guest

                            Welcome

                        @else

                               <table class="table table-bordered table-striped">
                                   <thead>
                                   <tr>
                                       <td>Address</td>
                                       <td>Postal Code</td>
                                       <td>State</td>
                                       <td>City</td>
                                       <td>Created</td>
                                       <td>Action</td>
                                   </tr>
                                   </thead>
                                   <tbody>
                                   @if(count($address) > 0)
                                       @foreach($address as $addresses)
                                           <tr>
                                               <td>{{$addresses->address}}</td>
                                               <td>{{$addresses->postal_code}}</td>
                                               <td>{{$addresses->states()->first() ? $addresses->states()->first()->state_name : 'Pending' }}</td>
                                               <td>{{$addresses->cities()->first() ? $addresses->cities()->first()->cities_name : 'Pending' }}</td>
                                               <td>{{\Carbon\Carbon::parse($addresses->created_at)->isoFormat('YYYY/MM/DD')}}</td>
                                               <td>
                                                   <a href="{{route('address.edit',['address' => $addresses->id])}}" class="btn btn-outline-primary">Edit</a>
                                                   <br/><a href="#" onclick="if(confirm(`{{'Sure'}}`)){event.preventDefault();document.getElementById('delete-form-{{$addresses->id}}').submit()};"  class="btn btn-danger">Delete</a>
                                                   <form id="delete-form-{{$addresses->id}}" action="{{route('address.destroy',['address' => $addresses->id])}}" method="post">
                                                       <input type="hidden" name="_method" value="DELETE">
                                                       @csrf
                                                   </form>
                                               </td>
                                           </tr>
                                       @endforeach
                                   @else
                                       <tr>
                                           <td class="text-center" colspan="6">
                                               No Data Available
                                           </td>
                                       </tr>
                                   @endif
                                   </tbody>
                               </table>

                        @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
