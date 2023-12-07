@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class=" align-items-center">
       <h1 class="h3">{{translate('Product Type Report')}}</h1>
	</div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <!--card body-->
            <div class="card-body">
                <form action="{{ route('product_type_report.index') }}" method="GET">
                    <div class="form-group row">
                        <div class="col">
                        <label class="col-form-label">{{translate('Sort by product type')}} </label>
                        <div class="col-md-5">
                            <select id="demo-ease" class="from-control aiz-selectpicker" name="product_type" id="product_type">
                                <option value="">{{ translate('Choose type') }}</option>
                                 <option value="running fabrics" @if ($product_type == 'running fabrics') selected @endif>{{translate('running fabrics')}}</option>
                                 <option value="ready made" @if ($product_type == 'ready made') selected @endif>{{translate('ready made')}}</option>
                            </select>
                        </div>
                        </div>
                        <div class="col-sm-2 align-self-end">
                                 <label>Start Date</label>
                                 <input type="date" class="form-control" value ="{{$start_date}}" name="start_date">
                        </div>
                        <div class="col-sm-2 align-self-end">
                                 <label>End Date</label>
                                 <input type="date" class="form-control" value ="{{$end_date}}" name="end_date">
                        </div>

                        <div class="col-md-2 align-self-end">
                            <button class="btn btn-primary" type="submit">{{ translate('Filter') }}</button>
                        </div>
                        <div class="dropdown align-self-end">
                           <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu2"                          data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           Export
                           </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                             <a href="{{ route('product_type_report') }}" type="button" class="btn btn-icon btn-light d-flex justify-content-between" style="width:65% !important;">pdf<i class="las la-print"></i></a>
                              <a href="{{ route('product_type_report_excel')}}" type="button" class="btn btn-icon btn-light d-flex justify-content-between" style="width:65% !important;">Excel<i class="las la-file-excel"></i></a>
                            </div>
                         </div>

                    </div>
                </form>
                <table class="table table-bordered aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>{{ translate('Customer Name') }}</th>
                            <th>{{ translate('Product Type') }}</th>
                            <th>{{ translate('Amount') }}</th>
                            <th>{{ translate('Date') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                     @foreach($products as $key => $product)
                      <tr>
                       <td>{{ $product->user->name}}</td>
                       <td>{{ $product->product_type}}</td>
                       <td>{{ $product->mrp}}</td>
                       <td>{{ $product->created_at->format('d-m-Y')}}</td>
                      </tr>
                   @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination mt-4">
                 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

