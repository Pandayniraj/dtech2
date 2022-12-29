@extends('layouts.master')

@section('head_extra')

 @include('partials._head_extra_select2_css')

@endsection
@section('content')

<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

 <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                 {!! $page_title !!}
                <small>{!! $page_description !!}</small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
 </section>

  <div class='row'>
        <div class='col-md-12'>
            <div class="box">
                <div class="box-body">
                   <form action="{{ route('admin.costterm.update', $costterm->id) }}" method="post">

                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">  
                              <label>Name</label>
                              <div class="input-group ">
                                <input type="text" name="name" id="" value="{{ $costterm->name }}" placeholder="Name" class="form-control " required="required"> 
                                <div class="input-group-addon">
                                    <a href="#"><i class="fa fa-calendar-alt"></i></a>
                                </div>
                              </div>
                            </div>
                         </div>
                        <div class="col-md-3">
                            <div class="form-group">  
                                  <label class="control-label">Dr</label>
                                 <div class="input-group">
                                    <select class="form-control select2" id="products" name="dr_ledger_id">
                                        <option value="">--Select--</option>
                                        @foreach($ledger_all as $ledger)
                                        <option value={{ $ledger->id }}@if($ledger->id==$costterm->dr_ledger_id) selected @endif>{{ $ledger->name }}</option>
                                        @endforeach   
                                    </select>
                                  {{-- {!! Form::select('dr_ledger_id', $ledger_all, $costterm->dr_ledger_id,null, ['class' => 'form-control project_id','id'=>'products', 'placeholder' => 'Please Select']) !!} --}}
                                 </div>
                            </div>   
                        </div>  
                        <div class="col-md-3">
                            <div class="form-group">  
                                  <label class="control-label">Cr</label><br>
                                 <div class="input-group">
                                    <select class="form-control select2" id="products" name="cr_ledger_id">
                                        <option value="">--Select--</option>
                                        @foreach($ledger_all as $ledger)
                                        <option value={{ $ledger->id }}@if($ledger->id==$costterm->cr_ledger_id) selected @endif>{{ $ledger->name }}</option>
                                        @endforeach   
                                    </select>
                                  {{-- {!! Form::select('cr_ledger_id', $ledger_all,$costterm->cr_ledger_id??'',null, ['class' => 'form-control project_id','id'=>'products', 'placeholder' => 'Please Select']) !!} --}}
                                 </div>
                            </div>   
                        </div>
                    </div>
                        <div class="row"> 
                            <div class="col-md-3">
                            <div class="form-group">
                                <div class="input-group">
                                <input type="Submit" value="Update CostTerm" class="btn btn-primary">
                                 <a href="{!! route('admin.costterm.index') !!}" class='btn btn-default'>{{ trans('general.button.cancel') }}</a>
                                </div>    
                                </div>
                        </div>
                        </div>
                        </form>
                        </div>
                  </div>
                </div>
            </div><!-- /.box-body -->

@endsection

@section('body_bottom')
    <link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
    <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>

    <script type="text/javascript">
    $(function() {
        $('.datepicker').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD', 
          sideBySide: true,
          allowInputToggle: true
        });

      });
</script>

<script type="text/javascript">
         $(document).ready(function() {
    $('.project_id').select2();
});
</script>

 @endsection

