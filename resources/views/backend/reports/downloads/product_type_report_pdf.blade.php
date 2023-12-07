<div style="margin-left:auto;margin-right:auto;">
<style media="all">
    @page {
		margin: 0;
		padding:0;
	}
	*{
		margin: 0;
		padding: 0;
	}
	body{
		line-height: 1.5;
		font-family: 'DejaVuSans', 'sans-serif';
		color: #333542;
	}
	div{
		font-size: 1rem;
	}
	.gry-color *,
	.gry-color{
		color:#878f9c;
	}
	table{
		width: 100%;
	}
	table th{
		font-weight: normal;
	}
	table.padding th{
		padding: .5rem .7rem;
	}
	table.padding td{
		padding: .7rem;
	}
	table.sm-padding td{
		padding: .2rem .7rem;
	}
	.border-bottom td,
	.border-bottom th{
		border-bottom:1px solid #eceff4;
	}
	.text-left{
		text-align:left;
	}
	.text-right{
		text-align:right;
	}
	.small{
		font-size: .85rem;
	}
	.strong{
		font-weight: bold;
	}
</style>

	@php
		$logo = get_setting('header_logo');
	@endphp

	<div style="background: #eceff4;padding: 1.5rem;">
		<table>
			<tr>
				<td>
					@if($logo != null)
						<img src="{{ uploaded_asset($logo) }}" height="40" style="display:inline-block;">
					@else
					<img src="{{ static_asset('assets/img/Zuri_Logo.pdf.png') }}" height="100" style="display:inline-block;">
					@endif
				</td>
                <td style="font-size: 1.5rem;" class="text-right strong">{{  translate('IN HOUSE SALE REPORT') }}</td>
			</tr>
		</table>

	</div>
	<div style="border-bottom:1px solid #eceff4;margin: 0 1.5rem;"></div>

    <div style="padding: 1.5rem;">
		<table class="padding text-left small border-bottom">
			<thead>
                <tr class="gry-color" style="background: #eceff4;">
                    <th width="50%">{{translate('Customer Name') }}</th>
                    <th width="50%">{{translate('Product Type') }}</th>
                    <th width="50%">{{translate('Amount') }}</th>
                    <th width="50%">{{translate('Date') }}</th>
                </tr>
			</thead>
			<tbody class="strong">
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
	</div>

</div>
