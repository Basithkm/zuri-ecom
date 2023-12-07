@extends('backend.layouts.app')

@section('content')
    @if($errors->any())
        @foreach($errors->all() as $error)
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{$error}}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
           </button>
         </div>
        @endforeach
    @endif
<div class="row">
    <div class="col-xl-10 mx-auto">
        <h6 class="fw-600">{{ translate('Customer Details Edit') }}</h6>
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">{{ translate('Customer Edit') }}</h6>
            </div>
             <div class="card-body">
                <form action="{{ route('customers.update', $user->id)}}" method="POST">
                    <input name="_method" type="hidden" value="POST">
                    <input type="hidden" name="id" value="$user->id">
                    @csrf
                    <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Customer Name')}}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="name" placeholder="{{ translate(' Name') }}" value="{{ $user->name}}" >
                            </div>
                    </div>
                    <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{translate('Email Address')}}</label>
                             <div class="col-md-8">
                             <input type="email" class="form-control" name="email" placeholder="{{ translate('Email') }}" value="{{ $user->email}}" >
                            </div>
                    </div>
                    <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{translate('Customer Address')}}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="address" placeholder="{{ translate('Address') }}" value="{{ $user->address}}" >
                            </div>
                    </div>
                    <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{translate('Customer Phone')}}</label>
                            <div class="col-md-8">
                                <input type="tel" class="form-control" name="phone" value="{{ $user->phone}}" placeholder="{{ translate  ('Phone') }}" >
                            </div>
                    </div>

                       <div class="text-right">
                        <button type="submit" class="btn btn-danger">{{ translate('SAVE') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection