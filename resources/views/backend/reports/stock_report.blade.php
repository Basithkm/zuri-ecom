@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class=" align-items-center">
       <h1 class="h3">{{translate('Product wise stock report')}}</h1>
	</div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <!--card body-->
            <div class="card-body">
                <form action="{{ route('stock_report.index') }}" method="GET">
                    <div class="form-group row offset-lg-2">
                        <label class="col-md-3 col-form-label">{{translate('Sort by Category')}} :</label>
                        <div class="col-md-5">
                            <select id="demo-ease" class="from-control aiz-selectpicker" name="category_id" required>
                                <option value="">{{ translate('Choose Category') }}</option>
                                @foreach (\App\Models\Category::all() as $key => $category)
                                    <option value="{{ $category->id }}" @if($sort_by == $category->id) selected @endif>{{ $category->getTranslation('name') }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary" type="submit">{{ translate('Filter') }}</button>
                        </div>

                           <div class="dropdown">
                           <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu2"                          data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           Export
                           </button>
                           <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                             <a href="{{ route('stock_report.download') }}" type="button" class="btn btn-icon btn-light d-flex justify-content-between" style="width:65% !important;">pdf<i class="las la-print"></i></a>
                              <a href="{{ route('stock_report_excel') }}" type="button" class="btn btn-icon btn-light d-flex justify-content-between" style="width:65% !important;">Excel<i class="las la-file-excel"></i></a>
                           </div>
                         </div>

                    </div>
                </form>
                <table class="table table-bordered aiz-table mb-0">
                    <thead>
                        <tr>
                            <th>{{ translate('Product Name') }}</th>
                            <th>{{ translate('Stock') }}</th>
                            <th>{{ translate('Num of Sale') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $key => $product)
                            @php
                                $qty = 0;
                                foreach ($product->stocks as $key => $stock) {
                                    $qty += $stock->qty;
                                }
                            @endphp
                            <tr>
                                <td>{{ $product->getTranslation('name') }}</td>
                                <td>{{ $qty }}</td>
                                <td>{{ $product->num_of_sale }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="aiz-pagination mt-4">
                    {{ $products->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
