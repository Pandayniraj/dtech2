@extends('layouts.master')
@section('content')

@include('partials._head_extra_select2_css')
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {!! $page_title ?? "Page Title" !!}
        <small>{!! $page_description ?? "Page description" !!}</small>
    </h1>
    {{-- Current Fiscal Year: <strong>{{ FinanceHelper::cur_fisc_yr()->fiscal_year}}</strong> --}}

    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<div class='row'>
    <div class='col-md-12'>
        <div class="box">
            <div class="box-body ">
                <form method="post" action="{{route('admin.expense.cost.center.update',$costCenter->id)}}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6 col-sm-12 form-group">
                            <label class="control-label">CDP</label>
                            <div class="input-group">
                                <select name="cdp_id" class="form-control select2 " style="width: 347px;">
                                    <option value="">Select CDP</option>
                                    @foreach($cdps as $cdp)
                                        <option value="{{ $cdp->id }}" {{ $cdp->id == $costCenter->cdp->id ? 'selected' : '' }}>{{$cdp->name}}</option>>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6 col-sm-12 form-group">
                            <label class="control-label">Name</label>
                            <div class="input-group">
                                <input type="text" name="name" class="form-control" placeholder="Name" required="" value="{{$costCenter->name}}">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div><div class="col-md-6 col-sm-12 form-group">
                            <label class="control-label">Code</label>
                            <div class="input-group">
                                <input type="text" name="code" class="form-control" placeholder="Code" required="" value="{{$costCenter->code}}">
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa   fa-database"></i></a>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                {!! Form::submit( trans('general.button.update'), ['class' => 'btn btn-primary', 'id' => 'btn-submit-edit'] ) !!}
                                <a href="{!! route('admin.expense.cost.center.index') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
    <script>
        $('.select2').select2();

    </script>
    @endsection
