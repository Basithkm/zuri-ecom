@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
	<div class="row align-items-center">
		<div class="col">
			<h1 class="h3">{{ translate('Banner') }}</h1>
		</div>
	</div>
</div>

<div class="card">
	<!-- @can('add_website_page') -->
		<div class="card-header">
			<h6 class="mb-0 fw-600">{{ translate('home slide 1') }}</h6>
			<a href="{{ route('home_slide.index') }}" class="btn btn-primary">{{ translate('Add New Banner') }}</a>
		</div>
	<!-- @endcan -->
	<div class="card-body">
		<table class="table aiz-table mb-0">
        <thead>
            <tr>
                <th data-breakpoints="lg"># Order</th>
                <th>{{translate('Image')}}</th>
				<th>{{translate('Button Name')}}</th>
                <th data-breakpoints="md">{{translate('URL')}}</th>
                <th class="text-right">{{translate('Actions')}}</th>
            </tr>
        </thead>
        <tbody>
        <tbody>
        	@foreach (\App\Models\Banner::where('section','section1')->get() as $key => $page)
        	<tr>
        		<td>{{ $key+1 }}</td>
				<td><img src="{{ uploaded_asset($page->image)}}" alt="Image" class="size-50px img-fit">
				</td>
        		<td>{{ $page->text }}</td>
                <td>{{ $page->url }}</td>
                <td class="text-right">
					<a href="{{ route('banners.edit', ['id'=>$page->id])}}" class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="Edit">
						 <i class="las la-pen-square"></i> 
					</a>
                    <a href="{{route('banners.delete', ['id'=>$page->id] )}}" class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="Delete">
                        <i class="las la-trash-alt"></i>
                    </a>
				</td>
        	</tr>
        	@endforeach
        </tbody>
    </table>
	</div>
</div>
<div class="card">
	<!-- @can('add_website_page') -->
		<div class="card-header">
			<h6 class="mb-0 fw-600">{{ translate('home section 2') }}</h6>
			<a href="{{ route('home_section2.index') }}" class="btn btn-primary">{{ translate('Add New Banner') }}</a>
		</div>
	<!-- @endcan -->
	<div class="card-body">
		<table class="table aiz-table mb-0">
        <thead>
            <tr>
                <th data-breakpoints="lg"># Order</th>
                <th>{{translate('Image')}}</th>
				<th>{{translate('column')}}</th>
                <th data-breakpoints="md">{{translate('URL')}}</th>
                <th class="text-right">{{translate('Actions')}}</th>
            </tr>
        </thead>
        <tbody>
        <tbody>
        	@foreach (\App\Models\Banner::where('section','section2')->get() as $key => $page)
        	<tr>
        		<td>{{ $key+1 }}</td>
				<td><img src="{{ uploaded_asset($page->image)}}" alt="Image" class="size-50px img-fit">
				</td>
        		<td>{{ $page->column }}</td>
                <td>{{ $page->url }}</td>
                <td class="text-right">
					<a href="{{ route('home_section2_edit', ['id'=>$page->id])}}" class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="Edit">
						 <i class="las la-pen-square"></i> 
					</a>
                    <a href="{{route('banners.delete', ['id'=>$page->id] )}}" class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="Delete">
                        <i class="las la-trash-alt"></i>
                    </a>
				</td>
        	</tr>
        	@endforeach
        </tbody>
    </table>
	</div>
</div>
<div class="card">
	<!-- @can('add_website_page') -->
		<div class="card-header">
			<h6 class="mb-0 fw-600">{{ translate('home section 3') }}</h6>
			<a href="{{ route('home_section3.index') }}" class="btn btn-primary">{{ translate('Add New Banner') }}</a>
		</div>
	<!-- @endcan -->
	<div class="card-body">
		<table class="table aiz-table mb-0">
        <thead>
            <tr>
                <th data-breakpoints="lg"># Order</th>
                <th>{{translate('Image')}}</th>
				<th>{{translate('Button Name')}}</th>
                <th data-breakpoints="md">{{translate('URL')}}</th>
                <th class="text-right">{{translate('Actions')}}</th>
            </tr>
        </thead>
        <tbody>
        <tbody>
        	@foreach (\App\Models\Banner::where('section','section3')->get() as $key => $page)
        	<tr>
        		<td>{{ $key+1 }}</td>
				<td><img src="{{ uploaded_asset($page->image)}}" alt="Image" class="size-50px img-fit">
				</td>
        		<td>{{ $page->text }}</td>
                <td>{{ $page->url }}</td>
                <td class="text-right">
					<a href="{{ route('banners.edit',['id' => $page->id]) }}" class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="Edit">
						 <i class="las la-pen-square"></i>
					</a>
                    <a href="{{route('banners.delete', ['id'=>$page->id] )}}" class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="Delete">
                        <i class="las la-trash-alt"></i>
                    </a>
				</td>
        	</tr>
        	@endforeach
        </tbody>
    </table>
	</div>
</div>
<div class="card">
	<!-- @can('add_website_page') -->
		<div class="card-header">
			<h6 class="mb-0 fw-600">{{ translate('home section 4') }}</h6>
			<a href="{{ route('home_section4.index') }}" class="btn btn-primary">{{ translate('Add New Banner') }}</a>
		</div>
	<!-- @endcan -->
	<div class="card-body">
		<table class="table aiz-table mb-0">
        <thead>
            <tr>
                <th data-breakpoints="lg"># Order</th>
                <th>{{translate('Image')}}</th>
				<th>{{translate('Button Name')}}</th>
                <th data-breakpoints="md">{{translate('URL')}}</th>
                <th class="text-right">{{translate('Actions')}}</th>
            </tr>
        </thead>
        <tbody>
        <tbody>
        	@foreach (\App\Models\Banner::where('section','section4')->get() as $key => $page)
        	<tr>
        		<td>{{ $key+1 }}</td>
				<td><img src="{{ uploaded_asset($page->image)}}" alt="Image" class="size-50px img-fit">
				</td>
        		<td>{{ $page->text }}</td>
                <td>{{ $page->url }}</td>
                <td class="text-right">
					<a href="{{ route('banners.edit',['id' => $page->id]) }}" class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="Edit">
						 <i class="las la-pen-square"></i> 
					</a>
                    <a href="{{route('banners.delete', ['id'=>$page->id] )}}" class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="Delete">
                        <i class="las la-trash-alt"></i>
                    </a>
				</td>
        	</tr>
        	@endforeach
        </tbody>
    </table>
	</div>
</div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection
