@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class=" align-items-center">
       <h1 class="h3">{{translate('Return report')}}</h1>
	</div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('return_report')}}" method="GET">
                        <div class="form-group row">
                            <div class="col-sm-3 align-self-end">
                                    <label>Start Date</label>
                                    <input  type="date" class="form-control" value ="{{$start_date}}" name="start_date" placeholder="translate('start date')">
                            </div>
                            <div class="col-sm-3">
                                    <label>End Date</label>
                                    <input  type="date" class="form-control" value ="{{$end_date}}" name="end_date">
                            </div>
                            <div class="col-md-2 align-self-end">
                                <button class="btn btn-primary" type="submit">{{ translate('Filter') }}</button>
                            </div>
                            <div class="dropdown align-self-end">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu2"                          data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Export
                            </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                <a href="{{ route('return_report.download')}}" type="button" class="btn btn-icon btn-light d-flex justify-content-between" style="width:65% !important;">pdf<i class="las la-print"></i></a>
                                <a href="{{ route('return_report_excel') }}" type="button" class="btn btn-icon btn-light d-flex justify-content-between" style="width:65% !important;">Excel<i class="las la-file-excel"></i></a>
                                </div>
                            </div>

                        </div>
                    </form>

                <table class="table table-bordered aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>{{ translate('Date') }}</th>
                            <th>{{ translate('Product Name') }}</th>
                            <th>{{ translate('Customer Name') }}</th>
                            <th>{{ translate('Order Numebr') }}</th>
                            <th>{{ translate('Quantity') }}</th>
                            <th>{{ translate('Price') }}</th>
                        </tr>
                    </thead>
                  

                    <tbody>
                    @foreach ($order_details as $key => $order_detail)
                        <tr>
                            <td>{{ $order_detail->updated_at->format('d-m-Y') }}</td>
                            <td>{{ $order_detail->product->name }}</td>
                            @php
                                $shippingAddress = json_decode($order_detail->order->shipping_address, true);
                            @endphp

                            <td>{{ $shippingAddress['name'] ?? 'N/A' }}</td>

                            <td>{{ $order_detail->order->code }}</td>
                            <td>{{ $order_detail->quantity }}</td>
                            <td>{{ $order_detail->price }}</td>
                           
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
