@extends('layouts.master')
@section('content')
<style type="text/css">
.select-mini {
  font-size: 10px;
  height: 25px;
  width: 90px;
}
 .nep-date-toggle{
        width: 120px !important;
    }
</style>
<link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        {{ ucfirst($page_title)}}
        <small>{!! $page_description ?? "Page description" !!}</small>
    </h1>
    <p><i class="fa fa-money"></i> Manage additional charge and its ledger</p>

    {{-- {{ TaskHelper::topSubMenu('topsubmenu.accounts')}} --}}

    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>

<div class='row'>
    <div class='col-md-12'>
        <!-- Box -->
        <div class="box box-primary">
             <form method="get" action="/admin/expenses">
            <div class="box-header with-border">

                &nbsp;
                <a class="btn btn-default btn-sm" href="{!! route('admin.costterm.create') !!}" title="Create New Expense">
                    Add New &nbsp; <i class="fa fa-plus-square"></i>
                </a>
                &nbsp;


                <div class="box-tools pull-right">
                    {{-- <a href="/admin/download/expenses/pdf/index" class="btn btn-success btn-xs float-right no-loading" style="margin-right: 20px;">PDF Download</a>
                    &nbsp; --}}

                    {{-- <button type="submit" name="submit" value="excel" class="btn btn-success btn-xs float-right no-loading">
                        Excel Download
                    </button> --}}
{{-- 
                    <butt href="/admin/download/expenses/excel/index" class="btn btn-success btn-xs float-right">Excel Download</a --}}
                    &nbsp;
                </div>
                <div class="wrap" style="margin-top:5px;">
            </div>
              </form>
            <div class="box-body">

                <div class="table-responsive">
                  
<table class="table table-hover table-bordered" id="clients-table">
<thead>
    <tr class="bg-maroon">
        <th>Sn</th>
        <th>ID</th>
        <th>Name</th>
        <th>Dr</th>
        <th>Cr</th>
        <th>Action</th>
  
        <!--  <th>Actions</th> -->
    </tr>
</thead>
<tbody>
    @foreach($costterms as $costterm)
    <tr>
        <td><b>{{$loop->iteration}}</b></td>
        <td>{{ $costterm->id }} </td>
        <td>
            {{ $costterm->name}}
        </td>
        <td>
            {{ $costterm->dr_ledger_id }}
        </td>
        <td>{{ $costterm->cr_ledger_id }}</td>

        <td>
           
            <a href="{!! route('admin.costterm.edit', $costterm->id) !!}" title="{{ trans('general.button.edit') }}"><i class="fa fa-edit"></i></a>
        
             <a href="{!! route('admin.costterm.confirm-delete', $costterm->id) !!}" data-toggle="modal" data-target="#modal_dialog" title="{{ trans('general.button.delete') }}"><i class="fa fa-trash deletable"></i></a>
        
            {{-- <i class="fa fa-trash text-muted" title="{{ trans('admin/clients/general.error.cant-delete-this-client') }}"></i> --}}
           
        </td>


    </tr>
    @endforeach
</tbody>
</table>

                </div> <!-- table-responsive -->

            </div><!-- /.box-body -->
        </div><!-- /.box -->
        {!! Form::close() !!}
    </div><!-- /.col -->

</div><!-- /.row -->



<div class="modal fade" id="announcement_show" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        </div>
    </div>
</div>
@endsection


<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
@include('partials._date-toggle')
<!-- DataTables -->
<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />
<link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet" type="text/css" />
<script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
<script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}" type="text/javascript"></script>
<script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}" type="text/javascript"></script>

<script src="{{ asset ("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js") }}"></script>

<script language="JavaScript">
    function toggleCheckbox() {
        checkboxes = document.getElementsByName('chkClient[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = !checkboxes[i].checked;
        }
    }
    $('.date-toggle-nep-eng1').datetimepicker({
            //inline: true,
            format: 'YYYY-MM-DD'
            , sideBySide: true
            , allowInputToggle: true,

        });
    $('.date-toggle-nep-eng1').nepalidatetoggle();
    $('.select2').select2();

</script>



<script>
    $(function() {
        $('#clients-table').DataTable({
            pageLength: 35
            , ordering: false
        });
    });
</script>
@endsection
