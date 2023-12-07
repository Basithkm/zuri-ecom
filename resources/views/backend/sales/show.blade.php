@extends('backend.layouts.app')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <div class="card">
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0">{{ translate('Order Details') }}</h1>
        </div>
        <div class="card-body">
            <div class="row gutters-5">
                <div class="col text-md-left text-center">
                </div>
                @php
                
                    $delivery_status = $order->delivery_status;
                    $payment_status = $order->payment_status;
                    $admin_user_id = App\Models\User::where('user_type', 'admin')->first()->id;
                @endphp

                <!--Assign Delivery Boy-->
                @if ($order->seller_id == $admin_user_id || get_setting('product_manage_by_admin') == 1)
                    
                    @if (addon_is_activated('delivery_boy'))
                        <div class="col-md-3 ml-auto">
                            <label for="assign_deliver_boy">{{ translate('Assign Deliver Boy') }}</label>
                            @if (($delivery_status == 'pending' || $delivery_status == 'confirmed' || $delivery_status == 'picked_up') && auth()->user()->can('assign_delivery_boy_for_orders'))
                                <select class="form-control aiz-selectpicker" data-live-search="true"
                                    data-minimum-results-for-search="Infinity" id="assign_deliver_boy">
                                    <option value="">{{ translate('Select Delivery Boy') }}</option>
                                    @foreach ($delivery_boys as $delivery_boy)
                                        <option value="{{ $delivery_boy->id }}"
                                            @if ($order->assign_delivery_boy == $delivery_boy->id) selected @endif>
                                            {{ $delivery_boy->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <input type="text" class="form-control" value="{{ optional($order->delivery_boy)->name }}"
                                    disabled>
                            @endif
                        </div>
                    @endif



                    <div class="col-md-3 ml-auto">
                        <label for="update_payment_status">{{ translate('Payment Status') }}</label>
                        @if (auth()->user()->can('update_order_payment_status'))
                            <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                                id="update_payment_status">
                                <option value="unpaid" @if ($payment_status == 'unpaid') selected @endif>
                                    {{ translate('Unpaid') }}
                                </option>
                                <option value="paid" @if ($payment_status == 'paid') selected @endif>
                                    {{ translate('Paid') }}
                                </option>
                                <option value="refunded" @if ($payment_status == 'refunded') selected @endif>
                                    {{ translate('Refunded') }}
                                </option>
                                
                            </select>
                        @else
                            <input type="text" class="form-control" value="{{ $payment_status }}" disabled>
                        @endif
                    </div>


                    <div class="col-md-3 ml-auto">
                        <label for="update_delivery_status">{{ translate('Order Status') }}</label>
                        @if (auth()->user()->can('update_order_delivery_status') && $delivery_status != 'delivered' && $delivery_status != 'cancelled')
                            <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                                id="update_delivery_status">


                                <option value="pending" @if ($delivery_status == 'pending') selected @endif>
                                    {{ translate('Pending') }}
                                </option>
                                <option value="confirmed" @if ($delivery_status == 'confirmed') selected @endif>
                                    {{ translate('Confirmed') }}
                                </option>
                                <option value="picked_up" @if ($delivery_status == 'picked_up') selected @endif>
                                    {{ translate('Picked Up') }}
                                </option>

                                <option value="ready_to_shiped" @if ($delivery_status == 'ready_to_shiped') selected @endif>
                                    {{translate('Ready to Shiped')}}
                                </option>

                                @if($order->shiprocket_status == "NEW")
                                    <option value="NEW" @if($delivery_status == 'NEW') selected @endif>
                                    {{ translate('NEW') }}
                                    </option>
                                 @endif

                                <option value="IN_TRANSIT" @if ($delivery_status == 'IN_TRANSIT') selected @endif>
                                    {{ translate('IN TRANSIT') }}
                                </option>
                                <option value="delivered" @if ($delivery_status == 'delivered') selected @endif>
                                    {{ translate('Delivered') }}
                                </option>
                                <option value="cancelled" @if ($delivery_status == 'cancelled') selected @endif>
                                    {{ translate('Cancel') }}
                                </option>
                                <option value="returned" @if ($delivery_status == 'returned') selected @endif>
                                    {{ translate('Returned') }}
                                </option>








                            </select>
                        @else
                            <input type="text" class="form-control" value="{{ $delivery_status }}" disabled>
                        @endif
                    </div>
                    <div class="col-md-3 ml-auto">
                        <label for="update_tracking_code">
                            {{ translate('AWB Code (optional)') }}
                        </label>
                        @if ($order->awb_code)

                        <input type="text" class="form-control" id="update_tracking_code"
                            value=" {{$order->awb_code}}" readonly>
                            @endif
                    </div>
                @endif
            </div>
            <div class="mb-3">
                <!-- @php


                    $removedXML = '<?xml version="1.0" encoding="UTF-8"?>';



                    
                @endphp
                {!! str_replace($removedXML, '', QrCode::size(100)->generate($order->code)) !!} -->
                
                <img src="https://api.qrserver.com/v1/create-qr-code/?data=https://shiprocket.co/tracking/{{$order->awb_code}}&size=100x100" alt="QR Code">

            </div>
            <div class="row gutters-5">
                <div class="col text-md-left text-center">
                    @if(json_decode($order->shipping_address))
                        <address>
                            <strong class="text-main">
                                {{ json_decode($order->shipping_address)->name }}
                            </strong><br>
                            {{ json_decode($order->shipping_address)->email }}<br>
                            {{ json_decode($order->shipping_address)->phone }}<br>
                            {{ json_decode($order->shipping_address)->address }}, {{ json_decode($order->shipping_address)->city }}, @if(isset(json_decode($order->shipping_address)->state)) {{ json_decode($order->shipping_address)->state }} - @endif {{ json_decode($order->shipping_address)->postal_code }}<br>
                            {{ json_decode($order->shipping_address)->country }}
                        </address>
                    @else
                        <address>
                            <strong class="text-main">
                            {{ optional($order->user)->name }}
                            </strong><br>
                            {{ optional($order->user)->email }}<br>
                            {{ optional($order->user)->phone }}<br>
                        </address>
                    @endif
                    @if ($order->manual_payment && is_array(json_decode($order->manual_payment_data, true)))
                        <br>
                        <strong class="text-main">{{ translate('Payment Information') }}</strong><br>
                        {{ translate('Name') }}: {{ json_decode($order->manual_payment_data)->name }},
                        {{ translate('Amount') }}:
                        {{ single_price(json_decode($order->manual_payment_data)->amount) }},
                        {{ translate('TRX ID') }}: {{ json_decode($order->manual_payment_data)->trx_id }}
                        <br>
                        <a href="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}" target="_blank">
                            <img src="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}" alt=""
                                height="100">
                        </a>
                    @endif
                </div>
                <div class="col-md-4 ml-auto">
                    <table>
                        <tbody>
                       
                            <tr>
                                <td class="text-main text-bold">{{ translate('Order #') }}</td>
                                <td class="text-info text-bold text-right"> {{ $order->code }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Order Status') }}</td>
                                <td class="text-right">
                                    @if ($delivery_status == 'delivered')
                                        <span class="badge badge-inline badge-success">
                                            {{ translate(ucfirst(str_replace('_', ' ', $delivery_status))) }}
                                        </span>
                                    @else
                                        <span class="badge badge-inline badge-info">
                                            {{ translate(ucfirst(str_replace('_', ' ', $delivery_status))) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Order Date') }} </td>
                                <td class="text-right">{{ date('d-m-Y h:i A', $order->date) }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">
                                    {{ translate('Total amount') }}
                                </td>
                                <td class="text-right">
                                    {{ single_price($order->grand_total) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Payment method') }}</td>
                                <td class="text-right">
                                    {{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Courier Partner') }}</td>
                                <td class="text-right">{{ $order->delivery_type }}</td>
                                
                            </tr>

                            <tr>


                                <td class="text-main text-bold">{{ translate('Delivery status') }}</td>

                                @if ($order->shiprocket_status)
                                <td class="text-right"><span class="badge badge-inline badge-info">
                                {{ translate(ucfirst(str_replace('_', ' ', $order->shiprocket_status))) }}
                                    </span></td>
                                @endif


                                
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr class="new-section-sm bord-no">
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    <table class="table-bordered aiz-table invoice-summary table">
                        <thead>
                            <tr class="bg-trans-dark">
                                <th data-breakpoints="lg" class="min-col">#</th>
                                <th width="10%">{{ translate('Photo') }}</th>
                                <th class="text-uppercase">{{ translate('Description') }}</th>
                                <!-- <th data-breakpoints="lg" class="text-uppercase">{{ translate('Courier Partner') }}</th> -->
                                <th data-breakpoints="lg" class="min-col text-uppercase text-center">
                                    {{ translate('Qty') }}
                                </th>
                                <th data-breakpoints="lg" class="min-col text-uppercase text-center">
                                    {{ translate('Price') }}</th>
                                <th data-breakpoints="lg" class="min-col text-uppercase text-right">
                                    {{ translate('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderDetails as $key => $orderDetail)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0)
                                            <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank">
                                                <img height="50" src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}">
                                            </a>
                                        @elseif ($orderDetail->product != null && $orderDetail->product->auction_product == 1)
                                            <a href="{{ route('auction-product', $orderDetail->product->slug) }}" target="_blank">
                                                <img height="50" src="{{ uploaded_asset($orderDetail->product->thumbnail_img) }}">
                                            </a>
                                        @else
                                            <strong>{{ translate('N/A') }}</strong>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0)
                                            <strong>
                                                <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank"
                                                    class="text-muted">
                                                    {{ $orderDetail->product->getTranslation('name') }}
                                                </a>
                                            </strong>
                                            <small>
                                                {{ $orderDetail->variation }}
                                            </small>
                                            <br>
                                            <small>
                                                @php
                                                    $product_stock = json_decode($orderDetail->product->stocks->first(), true);
                                                @endphp
                                                {{translate('SKU')}}: {{ $product_stock['sku'] }}
                                            </small>
                                            <br>



                                            @if($orderDetail->custom_id !=0)


                                            <input type="hidden" value="{{ $orderDetail->product->id }}" id="productIdInput{{ $key }}">    
                                            <input type="hidden" value="{{ $orderDetail->custom_id }}" id="CustomIdInput{{ $key }}">      
                                            <!-- <button onclick="fetchCustomPopup({{ $key }})" id="customButton{{ $key }}">Custom</button> -->


                                            <button type="submit" onclick="fetchCustomPopup({{ $key }})" id="customButton{{ $key }}" class="btn btn-success action-btn" style="font-size: 12px; padding: 3px 10px;">{{ translate('Custom') }}</button>


                                            @endif




                                            
                                        @elseif ($orderDetail->product != null && $orderDetail->product->auction_product == 1)
                                            <strong>
                                                <a href="{{ route('auction-product', $orderDetail->product->slug) }}" target="_blank"
                                                    class="text-muted">
                                                    {{ $orderDetail->product->getTranslation('name') }}

        
                                                </a>
                                            </strong>
                                        @else
                                            <strong>{{ translate('Product Unavailable') }}</strong>
                                        @endif
                                    </td>
                                    
                                    <!-- <td>
                                        @if ($order->shipping_type != null && $order->shipping_type == 'home_delivery')
                                            {{ translate('Home Delivery') }}
                                        @elseif ($order->shipping_type == 'pickup_point')
                                            @if ($order->pickup_point != null)
                                                {{ $order->pickup_point->getTranslation('name') }}
                                                ({{ translate('Pickup Point') }})
                                            @else
                                                {{ translate('Pickup Point') }}
                                            @endif
                                        @elseif($order->shipping_type == 'carrier')
                                            @if ($order->carrier != null)
                                                {{ $order->carrier->name }} ({{ translate('Carrier') }})
                                                <br>
                                                {{ translate('Transit Time').' - '.$order->carrier->transit_time }}
                                            @else
                                                {{ translate('Carrier') }}
                                            @endif
                                        @endif
                                        
                                        {{ $orderDetail->product->choice_options}}
                                    </td> -->
                                    
                                    <td class="text-center">
                                        {{ $orderDetail->quantity }}
                                    </td>
                                    <td class="text-center">
                                        {{ single_price($orderDetail->price / $orderDetail->quantity) }}
                                    </td>
                                    <td class="text-center">
                                        {{ single_price($orderDetail->price) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="clearfix float-right">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Sub Total') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('price')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Tax') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('tax')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Shipping') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->orderDetails->sum('shipping_cost')) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Coupon') }} :</strong>
                            </td>
                            <td>
                                {{ single_price($order->coupon_discount) }}

                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('TOTAL') }} :</strong>
                            </td>
                            <td class="text-muted h5">
                                {{ single_price($order->grand_total) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="no-print text-right">
                    <a href="{{ route('invoice.download', $order->id) }}" type="button" class="btn btn-icon btn-light"><i
                            class="las la-print"></i></a>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $('#assign_deliver_boy').on('change', function() {
            var order_id = {{ $order->id }};
            var delivery_boy = $('#assign_deliver_boy').val();
            $.post('{{ route('orders.delivery-boy-assign') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                delivery_boy: delivery_boy
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Delivery boy has been assigned') }}');
            });
        });
        $('#update_delivery_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_delivery_status').val();
            $.post('{{ route('orders.update_delivery_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                AIZ.plugins.notify('success','{{ translate('Delivery status has been updated') }}');
            });
        });
        $('#update_payment_status').on('change', function() {
            var order_id = {{ $order->id }};
            var status = $('#update_payment_status').val();
            $.post('{{ route('orders.update_payment_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Payment status has been updated') }}');
            });
        });
        $('#update_tracking_code').on('change', function() {
            var order_id = {{ $order->id }};
            var tracking_code = $('#update_tracking_code').val();
            $.post('{{ route('orders.update_tracking_code') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                tracking_code: tracking_code
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Order tracking code has been updated') }}');
            });
        });
    </script>



<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.0/dist/sweetalert2.min.css">

<!-- Include jQuery (if not already included) -->
<!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> -->

<!-- Include SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.0/dist/sweetalert2.all.min.js"></script>

<script>


function fetchCustomPopup(key) {

    var customId = document.getElementById('CustomIdInput' + key).value;
    var product_id = document.getElementById('productIdInput' + key).value;

    
    // var product_id = {!! json_encode($orderDetail->product_id) !!};
    var user_id = {!! json_encode($order->user_id) !!};
    var order_id = {{ $order->id }};


    console.log(product_id);
    console.log(customId);
    console.log(user_id);
    console.log(order_id);
    
    $.post('{{ route('order.custom_popup') }}', {
        _token: '{{ csrf_token() }}',
        customId: customId,
        order_id: order_id,
        product_id: product_id,
        user_id: user_id,
    }, function(response) {
        // Parse the JSON response
        var customvalue = response.custom;
        var order = response.order;
        var product = response.product;
        var user = response.user;

        // Accessing the 'user_id' and 'cart_id' values directly
        var product_og_id = product.id;
        var custom_id = customvalue.id;
        var order_og_id = order.id;
        var order_id = order.code;
        var cartId = customvalue.cart_id;
        var productName = product.name;
        var UserName = user.name;
        var UserId = user.id;
        var front_neck_depth = customvalue.front_neck_depth;
        var back_neck_depth = customvalue.back_neck_depth;
        var upper_bust = customvalue.upper_bust;
        var bust = customvalue.bust;
        var waist = customvalue.waist;
        var waist_round = customvalue.waist_round;
        var lower_waist = customvalue.lower_waist;
        var hip = customvalue.hip;
        var thigh_circumference = customvalue.thigh_circumference;
        var knee_circumference = customvalue.knee_circumference;
        var calf_circumference = customvalue.calf_circumference;
        var ankle_circumference = customvalue.ankle_circumference;
        var arm_hole = customvalue.arm_hole;
        var sleeve_length = customvalue.sleeve_length;
        var sleeve_circumference = customvalue.sleeve_circumference;
        var top_length = customvalue.top_length;
        var bottom_length = customvalue.bottom_length;
        var shoulder = customvalue.shoulder;
        var order_notes = customvalue.order_notes;
        var measurement = customvalue.measurement;
        var phone = user.phone;

        
        

        

        // Displaying the 'user_id' and 'cart_id' values with a custom image icon at the top center
        Swal.fire({
            // title: 'Custom Details',
            html: `

            
                <div style="text-align: center; padding: 20px;">
                    <img src="{{ static_asset('assets/img/Zuri_Logo.pdf.png') }}" alt="Custom Icon" style="width: 50px; height: 50px; margin-bottom: 20px;">

                    <div style="text-align: left;">
                        <div style="margin-bottom: 6px;">
                            <span style="color: #000000;">Order Number  :</span>${order_id}
                        </div>
                        <div style="margin-bottom: 6px;">
                            <span style="color:#000000;">Product Name :</span>${productName}
                        </div>
                        <div style="margin-bottom: 6px;">
                            <span style="color: #000000;">Customer Name :</span>${UserName}
                        </div>
                        <div style="margin-bottom: 6px;">
                            <span style="color: #000000;">Phone :</span>${phone}
                        </div>
                    </div>
                </div>

                <table style="width: 100%; border-collapse: collapse; border: 1px solid #000;">



                <tr>
                    <th style="width: 50px; height: 50px; border: 1px solid #000;"><span style="color: #000000;">Descrpition</span></th>
                    <th style="width: 50px; height: 50px; border: 1px solid #000;">Measurement in ${measurement}</th>
                </tr>
                <tr>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;"><span style="color: #000000;">Front Neck Depth</span></td>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;">${front_neck_depth}</td>
                </tr>
                <tr>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;"><span style="color: #000000;">Back Neck Depth</span></td>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;">${back_neck_depth}</td>                
                <tr>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;"><span style="color: #000000;">Upper Bust</span></td>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;">${upper_bust}</td>       
                </tr>    
                    

                <tr>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;"><span style="color: #000000;">Bust</span></td>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;">${bust}</td>
                </tr>
                <tr>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;"><span style="color: #000000;">Waist</span></td>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;">${waist}</td>
                </tr>
                <tr>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;"><span style="color: #000000;">Waist Round</span></td>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;">${waist_round}</td>                
                <tr>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;"><span style="color: #000000;">Lower Waist</span></td>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;">${lower_waist}</td>       
                </tr>    


                <tr>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;"><span style="color: #000000;">Hip</span></td>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;">${hip}</td>
                </tr>
                <tr>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;"><span style="color: #000000;">Thigh Circumference</span></td>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;">${thigh_circumference}</td>
                </tr>
                <tr>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;"><span style="color: #000000;">Knee Circumference</span></td>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;">${knee_circumference}</td>                
                <tr>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;"><span style="color: #000000;">Calf Circumference</span></td>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;">${calf_circumference}</td>       
                </tr>    


                <tr>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;"><span style="color: #000000;">Ankle Circumference</span></td>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;">${ankle_circumference}</td>
                </tr>
                <tr>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;"><span style="color: #000000;">Arm Hole</span></td>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;">${arm_hole}</td>
                </tr>
                <tr>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;"><span style="color: #000000;">Sleeve Length</span></td>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;">${sleeve_length}</td>                
                <tr>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;"><span style="color: #000000;">Sleeve Circumference</span></td>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;">${sleeve_circumference}</td>       
                </tr>   
                
                <tr>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;"><span style="color: #000000;">Top Length</span></td>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;">${top_length}</td>
                </tr>

                <tr>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;"><span style="color: #000000;">Bottom Length</span></td>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;">${bottom_length}</td>
                </tr>
                <tr>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;"><span style="color: #000000;">Shoulder</span></td>
                    <td style="width: 50px; height: 50px; border: 1px solid #000;">${shoulder}</td>
                </tr>
               

            </table>
            <br>

            <h2 style="text-align: left; margin-top: 20px;">Note:</h2>
            <p style="text-align: left; margin: 30px;">${order_notes}.</p>


                    <form action="{{ route('pdf-download.custom') }}" method="post">
                    @csrf
                        <input type="hidden" id="productID" name="productID" value = "${product_og_id}"  required><br>
                        <input type="hidden" id="customID" name="customID" value="${custom_id}" required><br>
                        <input type="hidden" id="userID" name="userID" value="${UserId}" required><br>
                        <input type="hidden" id="orderID" name="orderID" value="${order_og_id}" required><br>
                        
                        <button type="submit" name="button"   value="publish" class="btn btn-danger action-btn">{{ translate('PDF') }}</button>

                    </form>




            
            
                
            `,
            showCloseButton: true,
            showCancelButton: false,
            showConfirmButton: false,
            focusConfirm: false,
            customClass: {
                title: 'custom-popup-title',
                htmlContainer: 'custom-popup-container',
                closeButton: 'custom-popup-close',
            },
            // Make the modal responsive by setting width and height as percentages
            width: '50%', // Adjust the percentage as needed
            height: '50%', // Adjust the percentage as needed
        });
    });
}


</script>








@endsection