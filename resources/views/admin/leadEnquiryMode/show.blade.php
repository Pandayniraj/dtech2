@extends('layouts.master')

@section('head_extra')
    <!-- Select2 css -->
    @include('partials._head_extra_select2_css')

@endsection

@section('content')
    <div class='row'>
        <div class='col-md-12'>
        	<div class="box box-primary">
            	<div class="box-header with-border">
                	<h3>{!! trans('admin/lead-enquiry-mode/general.page.show.section-title') !!}</h3>
                </div>
                <div class="box-body">
    
                    {!! Form::model($LeadEnquiryMode, ['route' => 'admin.leadenquirymode.index', 'method' => 'GET']) !!}
    
                    <div class="content">
                        <div class="form-group">
                            {!! Form::label('name', trans('admin/lead-enquiry-mode/general.columns.name')) !!}
                            {!! Form::text('name', null, ['class' => 'form-control', 'readonly']) !!}
                        </div>
                        <div class="form-group">
                            <label>
                                {!! Form::checkbox('enabled', '1', $LeadEnquiryMode->enabled, ['disabled']) !!} {!! trans('general.status.enabled') !!}
                            </label>
                        </div>                        
                        <div class="form-group">
                            {!! Form::submit(trans('general.button.close'), ['class' => 'btn btn-primary']) !!}
                            @if ( $LeadEnquiryMode->isEditable() || $LeadEnquiryMode->canChangePermissions() )
                                <a href="{!! route('admin.leadenquirymode.edit', $LeadEnquiryMode->id) !!}" title="{{ trans('general.button.edit') }}" class='btn btn-default'>{{ trans('general.button.edit') }}</a>
                            @endif
                        </div>
                        {!! Form::close() !!}
                    </div><!-- /.content -->
    
                </div><!-- /.box-body -->
            </div>
        </div><!-- /.col -->

    </div><!-- /.row -->

@endsection

@section('body_bottom')
    <!-- Select2 js -->
    @include('partials._body_bottom_select2_js_user_search')
@endsection
