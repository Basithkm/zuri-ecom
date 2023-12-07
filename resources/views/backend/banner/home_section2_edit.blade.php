@extends('backend.layouts.app')
@section('content')

<div class="row">
    <div class="col-xl-10 mx-auto">
        <h6 class="fw-600">{{ translate('Home Page Settings') }}</h6>

        {{-- Home Slider --}}
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">{{ translate('Home Slider') }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('update_home2', $banner->id) }}" method="POST" enctype="multipart/form-data">
                <input name="_method" type="hidden" value="POST">
                <input type="hidden" name="id" value="{{ $banner->id }}">
                    @csrf
                    <div class="col-md-8 mb-2">
                        <label for="">Select column</label>
                        <select class="form-control aiz-selectpicker" name="column" id="column" data-live-search="true">
                                <option value="{{$banner->column}}">{{ translate('Choose Column') }}</option>
                                 <option value="column1" {{$banner->column == 'column 1' ? 'selected' : '' }}>{{ translate('Column 1') }}</option>
                                 <option value="column2" {{$banner->column == 'column 2' ? 'selected' : '' }}>{{ translate('Column 2') }}</option>
                                 <option value="column3" {{$banner->column == 'column 3' ? 'selected' : '' }}>{{ translate('Column 3') }}</option>
                                 <option value="column4" {{$banner->column == 'column 4' ? 'selected' : '' }}>{{ translate('Column 4') }}</option>
                                </select>
                    </div>
                                <div class="col-md-9">
                                    <label for="">Category</label>
                                 <div class="form-group">
                                <select class="form-control aiz-selectpicker" name="category_id" id="category_id" data-live-search="true" required>
                                 @if ($banner->category)
                               <option value="{{ $banner->category_id }}">{{ $banner->category->name}}</option>
                               @endif
                                 @foreach ($categories as $category)
                                @if ($banner->category_id != $category->id)
                                   <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endif
                               @endforeach
                            </select>
                             </div>
                            </div>
                        

                    
                    <div class="col-md">
                                <Label>Url</Label>
                                    <div class="form-group">
                                        <input type="hidden" name="types[]" value="home_slider_links">
                                        <input type="text" class="form-control" name="url" placeholder="url(https:)" name="home_slider_links[]"
                                         value="{{$banner->url}}">
                                    </div>
                    </div>
                    
                    <div class="form-group">
                        <label>{{ translate('Banner image for web') }}</label>
                        <div class="home-slider-target">
                            @if (get_setting('home_slider_images') != null)
                                @foreach (json_decode(get_setting('home_slider_images'), true) as $key => $value)
                                    <div class="row gutters-5">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <div class="input-group" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                    <input type="hidden" name="types[]" value="home_slider_images">
                                                    <input type="hidden" name="image" class="selected-files" value="{{ json_decode(get_setting('home_slider_images'), true)[$key] }}">
                                                </div>
                                                <div class="file-preview box sm"></div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                    <input type="hidden" name="types[]" value="home_slider_images">
                                                    <input type="hidden" name="image" class="selected-files" value="{{ json_decode(get_setting('home_slider_images'), true)[$key] }}">
                                                </div>
                                                <div class="file-preview box sm"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" lang="en"  placeholder="{{ translate('Text') }}" name="text[]" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div class="form-group">
                                                <input type="hidden" name="types[]" name="url" value="{{$banner->url}}" value="home_slider_links" >
                                                <input type="text" class="form-control" placeholder="http://" name="home_slider_links[]" value="{{ json_decode(get_setting('home_slider_links'), true)[$key] }}">
                                            </div>
                                        </div>
                                        <div class="col-md-auto">
                                            <div class="form-group">
                                                <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                                    <i class="las la-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            <!-- Additional fields -->
                            <div class="row gutters-5 d-flex flex-column">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                            </div>
                                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                            <input type="hidden" name="types[]" value="home_slider_images">
                                            <input type="hidden" name="image" class="selected-files"
                                            value = "{{ $banner->image}}">
                                        </div>
                                        <div class="file-preview box sm"></div>
                                    </div>
                                </div>
                               
                               </div>
                          
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{ translate('Banner image for mobile') }}</label>
                        <div class="home-slider-target">
                            @if (get_setting('home_slider_images') != null)
                                @foreach (json_decode(get_setting('home_slider_images'), true) as $key => $value)
                                    <div class="row gutters-5">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <div class="input-group" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                    <input type="hidden" name="types[]" value="home_slider_images">
                                                    <input type="hidden" name="mobile_image" class="selected-files" value="{{ json_decode(get_setting('home_slider_images'), true)[$key] }}">
                                                </div>
                                                <div class="file-preview box sm"></div>
                                            </div>
                                            <div class="form-group">
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                    <input type="hidden" name="types[]" value="home_slider_images">
                                                    <input type="hidden" name="mobile_image" class="selected-files" value="{{ json_decode(get_setting('home_slider_images'), true)[$key] }}">
                                                </div>
                                                <div class="file-preview box sm"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" lang="en"  placeholder="{{ translate('Text') }}" name="text[]" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div class="form-group">
                                                <input type="hidden" name="types[]" value="home_slider_links">
                                                <input type="text" class="form-control" placeholder="http://" name="home_slider_links[]" value="{{ json_decode(get_setting('home_slider_links'), true)[$key] }}">
                                            </div>
                                        </div>
                                        <div class="col-md-auto">
                                            <div class="form-group">
                                                <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                                    <i class="las la-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            <!-- Additional fields -->
                            <div class="row gutters-5 d-flex flex-column">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <div class="input-group" data-toggle="aizuploader" data-type="image">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                            </div>
                                            <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                            <input type="hidden" name="types[]" value="home_slider_images">
                                            <input type="hidden" name="mobile_image" class="selected-files" value="{{ $banner->mobile_image}}">
                                        </div>
                                        <div class="file-preview box sm"></div>
                                    </div>
                                </div>
                                
                               </div>
                          
                        </div>
                    </div>
                   
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">{{ translate('SAVE') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script type="text/javascript">
    $(document).ready(function(){
        AIZ.plugins.bootstrapSelect('refresh');
        // Toggle second field on button click
        $('#add-new-btn').click(function() {
            $('.home-slider-target .d-none').removeClass('d-none');
        });
    });
</script>
@endsection
