@extends('backend.layouts.app')
@section('content')

<div class="row">
    <div class="col-xl-10 mx-auto">
        <h6 class="fw-600">{{ translate('Home Page Settings') }}</h6>
        {{-- Home Slider --}}
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">{{ translate('Home Slider 3') }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('store_home3') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                      <div class="row gutters-5">
                                <div class="col-md-9">
                                    <label for="">Order</label>
                                    <div class="form-group">
                                        <input type="text" lang="en" placeholder="{{ translate('order #') }}" name="order[]" class="form-control" required>
                                    </div>
                                </div>
                    </div>
                       <div class="row gutters-5" id="category">
                                <div class="col-md-9">
                                    <label for="">Category</label>
                                    <div class="form-group">
                                    <select class="form-control aiz-selectpicker" name="category_id" id="category_id" data-live-search="true" required>
                                   @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                   </select>
                                    </div>
                                </div>
                    </div>
                                
                        <!-- Additional text fields -->
                        <div class="row gutters-5">
                                <div class="col-md-9">
                                    <label for="">Button Name</label>
                                    <div class="form-group">
                                        <input type="text" lang="en" placeholder="{{ translate('Button Name') }}" name="text[]" class="form-control" required>
                                    </div>
                                </div>
                        </div>
                        <div class="row gutters-5">
                        <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="">Url</label>
                                        <input type="hidden" name="types[]" value="home_slider_links">
                                        <input type="text" class="form-control" placeholder="url(https:)" name="home_slider_links[]">
                                    </div>
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
                                                    <input type="hidden" name="image[]" class="selected-files" value="{{ json_decode(get_setting('home_slider_images'), true)[$key] }}">
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
                                                    <input type="hidden" name="image[]" class="selected-files" value="{{ json_decode(get_setting('home_slider_images'), true)[$key] }}">
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
                                            <input type="hidden" name="image[]" class="selected-files">
                                        </div>
                                        <div class="file-preview box sm"></div>
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
                                                    <input type="hidden" name="mobile_image[]" class="selected-files" value="{{ json_decode(get_setting('home_slider_images'), true)[$key] }}">
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
                                                    <input type="hidden" name="mobile_image[]" class="selected-files" value="{{ json_decode(get_setting('home_slider_images'), true)[$key] }}">
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
                                            <input type="hidden" name="mobile_image[]" class="selected-files">
                                        </div>
                                        <div class="file-preview box sm"></div>
                                    </div>
                                </div>
                               
                               </div>
                            
                            <!-- Additional text fields -->
                           
                        </div>
                    </div>
                            <!-- Additional subtext fields -->
                            <!-- <div class="row gutters-5">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="text" lang="en" placeholder="{{ translate('SubText') }}" name="subtext[]" class="form-control" required>
                                    </div>
                                </div>
                               </div> -->
                            
                            <!-- Additional colour fields -->
                            <!-- <div class="row gutters-5">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <input type="text" lang="en" placeholder="{{ translate('Colour') }}" name="colour[]" class="form-control" required>
                                    </div>
                                </div>
                                </div> -->
                                 <!-- Additional subtext fields -->
                          
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
