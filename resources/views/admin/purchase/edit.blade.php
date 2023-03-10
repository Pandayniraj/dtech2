@extends('layouts.master')

@section('head_extra')
<!-- Select2 css -->
@include('partials._head_extra_select2_css')

<style>
	.panel .mce-panel {
		border-left-color: #fff;
		border-right-color: #fff;
	}

	.panel .mce-toolbar,
	.panel .mce-statusbar {
		padding-left: 20px;
	}

	.panel .mce-edit-area,
	.panel .mce-edit-area iframe,
	.panel .mce-edit-area iframe html {
		padding: 0 10px;
		min-height: 350px;
	}

	.mce-content-body {
		color: #555;
		font-size: 14px;
	}

	.panel.is-fullscreen .mce-statusbar {
		position: absolute;
		bottom: 0;
		width: 100%;
		z-index: 200000;
	}

	.panel.is-fullscreen .mce-tinymce {
		height: 100%;
	}

	.panel.is-fullscreen .mce-edit-area,
	.panel.is-fullscreen .mce-edit-area iframe,
	.panel.is-fullscreen .mce-edit-area iframe html {
		height: 100%;
		position: absolute;
		width: 99%;
		overflow-y: scroll;
		overflow-x: hidden;
		min-height: 100%;
	}
	input.form-control{
		min-width: 55px !important;
	}
	select{
		min-width: 80px !important;

	}
	.p_sn{
		max-width: 3px !important;
	}
    .c_sn{
        max-width: 3px !important;
    }
	@media only screen and (max-width: 770px) {
		input.total{
			width: 140px !important;
		}
	}
    span.select2.select2-container.select2-container--default {
    width: 100% !important;
}
.form-control {
    border-radius: 5px !important;
    box-shadow: none;
    border-color: #3c8dbc7a;
    height: 30px !important;
}
.select2-container .select2-selection--single {
    height: 30px !important;
}
.select2-container--default .select2-selection--single {
    background-color: #fff;
    border: 1px solid #3c8dbc7a !important;
    border-radius: 5px !important;
}
tr.bg-info.tr-heading th {
    border-left: 1px solid #fff;
    padding: 3px;
}
.table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
    border-top: none !important;
}
.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
    background-color: #fff !important;
    opacity: 1;
}
.panel-footer {
    padding: 10px 15px;
    background-color: #fff !important;
    border-top: none !important;
}
.box {
    border-radius: 12px !important;
    box-shadow: rgb(100 100 111 / 20%) 0px 7px 29px 0px !important;
    border-top: none !important;
}
.form-control {
    display: inline !important;
    font-size: 14px !important;
}
.col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
    position: relative;
    min-height: 1px;
    padding-right: 5px;
    padding-left: 5px;
    margin-top: -4px !important;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
    padding: 4px !important;
}
.select2-container .select2-selection--single .select2-selection__rendered {
    font-size: 14px;
}
.input-sm {
        padding: 5px 2px !important;
}
i.remove-this.icon.fa.fa-trash.deletable {
    margin-top: 4px;
}
</style>
@endsection

@section('content')
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>
<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
    <h1>
        Purchase {{ucfirst(trans(\Request::get('type')))}} edit
        <small> Purchase {{ucfirst(trans(\Request::get('type')))}}
        </small>
    </h1>
    {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false) !!}
</section>
@php
$costterms=\App\Models\CostTerm::pluck('name');
@endphp

<div class='row'>
    <div class='col-md-12'>
        <div class="box-body">


            <div id="orderFields" style="display: none;">
                <table class="table">
                    <tbody id="more-tr">
                        <tr>
                            <td class='p_sn'></td>
                            <td>
                                <select class="form-control input-sm input-sm select2 product_id getid select-product-id-2" name="product_id_new[]" required="required">
                                    @if(isset($products))
                                    <option value="">Select Products</option>
                                    @foreach($products as $key => $pk)
                                    <option value="{{ $pk->id }}">{{ $pk->name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </td>
                             <td>
                                <input type="number" class="form-control input-sm input-sm quantity" name="quantity_new[]" placeholder="Quantity"  required="required" step=".01" autocomplete="off">
                            </td>
                            <td>
                                <input type="text" class="form-control input-sm input-sm price" name="price_new[]" placeholder="Price" value="@if(isset($orderDetail->price)){{ $orderDetail->price }}@endif" required="required" autocomplete="off">
                            </td>


                            <td>
                                <input type="number" name="dis_amount_new[]"
                                class="form-control input-sm input-sm discount_amount_line" placeholder="Discount" step="any" value="0">
                            </td>


                            <td>
                                <select name='units_new[]' class="form-control input-sm input-sm units">
                                    <option value="">Unit</option>
                                    @foreach($prod_unit as $pu)
                                    <option value="{{ $pu->id }}">{{ $pu->symbol }}</option>
                                    @endforeach
                                </select>
                            </td>


                             <td class="col-sm-1">
                                <select class="form-control input-sm input-sm tax_rate_line" name="tax_type_new[]">
                                    <option value="0">Exempt(0)</option>
                                    <option value="13">VAT(13)</option>
                                </select>


                            </td>

                            <td style="display: none;">

                                <input type="number" class="form-control input-sm input-sm tax_amount_line"
                                name="tax_amount_new[]" value="0" readonly="readonly">
                            </td>
                            <td>
                                <input type="number" class="form-control input-sm tds_rate_line input-sm" name="tds_per[]" value="0.1" step=".01" autocomplete="off"/>
                            </td>
                            <td style="display: none;">
                                <input type="number" class="form-control input-sm tds_rate_amount input-sm" name="tds_amount[]" value="0" step=".01"/>
                            </td>


                            <td>
                                <input type="number" class="form-control input-sm  input-sm total" name="total_new[]" placeholder="Total" step="any" value="@if(isset($orderDetail->total)){{ $orderDetail->total }}@endif" readonly="readonly" style="float:left; width:70%;">
                                <a href="javascript::void(1);" style="width: 10%;">
                                    <i class="remove-this icon fa fa-trash deletable" style="float: right; color: red;"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="CustomOrderFields" style="display: none;">
                <table class="table">
                    <tbody id="more-custom-tr">
                        <tr>
                            <td class='p_sn'></td>
                            <td>
                                <input type="text" class="form-control input-sm input-sm product" name="custom_items_name_new[]" value="" placeholder="Product" autocomplete="off">
                            </td>
                             <td>
                                <input type="number" class="form-control input-sm input-sm quantity" name="custom_items_qty_new[]" placeholder="Quantity" required="required" step=".01" autocomplete="off">
                            </td>
                            <td>
                                <input type="text" class="form-control input-sm input-sm price" name="custom_items_price_new[]" placeholder="Price"
                                value="@if(isset($orderDetail->price)){{ $orderDetail->price }}@endif"

                                required="required" autocomplete="off">
                            </td>

                            <td>
                                <input type="number" name="custom_dis_amount_new[]"
                                class="form-control input-sm input-sm discount_amount_line" placeholder="Discount" step="any" value="0">
                            </td>


                            <td>
                                <select name='custom_units_new[]' class="form-control input-sm input-sm">
                                    <option value="">Unit</option>
                                    @foreach($prod_unit as $pu)
                                    <option value="{{ $pu->id }}" >{{ $pu->symbol }}</option>
                                    @endforeach
                                </select>
                            </td>

                            <td class="col-sm-1">
                                <select class="form-control input-sm input-sm tax_rate_line" name="custom_tax_type_new[]">
                                    <option value="0">Exempt(0)</option>
                                    <option value="13">VAT(13)</option>
                                </select>


                            </td>

                            <td>

                                <input type="number" class="form-control input-sm input-sm tax_amount_line"
                                name="custom_tax_amount_new[]" value="0" readonly="readonly">
                            </td>

                            <td>
                                <input type="number" class="form-control input-sm input-sm total" name="custom_total_new[]" step='any' placeholder="Total" value="@if(isset($orderDetail->total)){{ $orderDetail->total }}@endif" readonly="readonly" style="float:left; width:70%;">
                                <a href="javascript::void(1);" style="width: 10%;">
                                    <i class="remove-this icon fa fa-trash deletable" style="float: right; color: red;"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="orderCostFields" style="display: none;">
                <table class="table">
                    <tbody id="more-tr">
                        <tr>
                            <td class='c_sn'></td>
                            <td>
                                <select class="form-control input-sm select2 cost_type" name="cost_type[]" required="required" >
                                    {{-- <option value="">Select Cost</option> --}}

                                    <option value="">Select Cost</option>
                                    @foreach ($costterms as $term)
                                        <option value="{{$term}}">{{$term}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select class="form-control input-sm select2 product_id select-product-id-2" name="cost_product_id[]" required="required" >

                                    <option value="">Select Product</option>
                                    <option value="ALL">ALL Products</option>
                                    <option value="None">None</option>
                                    @foreach($products as $key => $pk)
                                    <option value="{{ $pk->id }}" @if(isset($orderDetail->product_id) && $orderDetail->product_id == $pk->id) selected="selected"@endif>{{ $pk->name }} (In Stock {{StockHelper::getRemaingStocks($pk->id)}})</option>
                                    @endforeach

                                </select>
                            </td>
                            <td class="col-sm-1">
                                <select name='method[]' class="form-control input-sm method">
                                    <option value="">--SELECT Method--</option>
                                    <option value="Quantity">Quantity</option>
                                    <option value="Value">Value</option>

                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control input-sm amount" name="amount[]" placeholder="Amount" value="" required="required" autocomplete="off">
                            </td>
                            <td>
                                <select name='debit_account[]' class="form-control input-sm select2 debit_account">
                                    <option value="">--Select--</option>
                                    @foreach ($ledger_all as $ledgerid=>$ledgername)
                                    <option value="{{$ledgerid}}">{{$ledgername}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name='credit_account[]' class="form-control input-sm select2 credit_account">
                                    <option value="">--Select--</option>
                                    @foreach ($ledger_all as $ledgerid=>$ledgername)
                                    <option value="{{$ledgerid}}">{{$ledgername}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="col-sm-1">
                                <input type="text" name="description[]" class="form-control input-sm description input-sm" placeholder="Description"  style="width:80%;>

                                <a href="javascript::void(1);" style="width:10%;">
                                    <i class="remove-this icon fa fa-trash deletable" style="float: right; color: red; margin-top: 6px;"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-md-12">
                <div class="panel panel-bordered">
                    {!! Form::model( $order, ['route' => ['admin.purchase.update', $order->id], 'method' => 'PUT', 'id'=>"mypurchaseform"] ) !!}

                    <div class="panel-body">
                        <input type="hidden" name="supplier_type" value='supplier'>

                       {{--  OR
                        <label class="bg-yellow">
                            &nbsp;&nbsp; From Cash & Equivalents
                            <input type="radio" name="supplier_type" value="cash_equivalent" @if($order->supplier_type == 'cash_equivalent')checked="" @endif>
                            &nbsp;&nbsp;
                        </label> --}}

                        <div class="col-md-12" style="margin-top: 7px; text-align: right;">
                            <div class="form-group">
                                <input type="checkbox" id="is_import" name="is_import" value="1" @if($order->is_import==1) checked
                                @endif onclick="ShowImportHideDiv(this)">
                                <label for="is_import">Is Import</label>
                            </div>
                        </div>

                        <div class="col-md-12">
                            {{-- <div class="col-md-4 form-group">
                                <label>Select Product Type:</label>
                                    <select class="form-control producttype select2" name="product_type">
                                        <option class="form-control input-sm input" value="" disabled selected>Select Any One</option>
                                        <option value="bills" {{ ($order->purchase_type== 'bills') ? 'selected':'' }}>Bills</option>
                                        <option value="assets" {{ ($order->purchase_type== 'assets') ? 'selected':'' }} >Assets</option>
                                        <option value="services" {{ ($order->purchase_type== 'services') ? 'selected':'' }}>Services</option>
                                    </select>
                                    
                            </div> --}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Select Supplier <i class="imp">*</i></label>
                                    <select class="form-control input-sm input-sm customer_id select2" name="customer_id" style="width: 400px;" id="customers">
                                        <option class="input input-lg" value="">Select Supplier</option>
                                         @if(isset($clients))
                                    @foreach($clients as $key => $uk)
                                    <option value="{{ $uk->id }}" @if($order && $uk->id == $order->supplier_id){{ 'selected="selected"' }}@endif>{{ '('.$uk->id.') '.$uk->name.' ('.$uk->vat.')' }} [{{ $uk->locations->city ??'' }}]</option>
                                    @endforeach
                                    @endif
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Order Date:</label>

                                    <div class="date">

                                        <input type="text" class="form-control input-sm input-sm pull-right date-toggle-nep-eng" name="ord_date" value="{{ $order->ord_date }}" id="ord_date">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Delivery Date:</label>

                                    <div class="date">

                                        <input type="text" class="form-control input-sm input-sm pull-right date-toggle-nep-eng" name="delivery_date" value="{{ $order->delivery_date }}" id="delivery_date">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Supplier Bill Date:</label>

                                    <div class="date">

                                        <div id='dateselectors'>
                                            <input type="text" class="form-control input-sm input-sm pull-right date-toggle-nep-eng" name="bill_date" value="{{ $order->bill_date }}" id="bill_date">
                                        </div>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Supplier Payment Date:</label>

                                    <div class="date">

                                        <div id='dateselectors1'>
                                            <input type="text" class="form-control input-sm input-sm pull-right date-toggle-nep-eng" name="due_date" value="{{ $order->due_date }}" id="due_date">
                                        </div>
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>
                        </div>    

                        <div class="col-md-12">
                           
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Status:</label>
                                    <select type="text" class="form-control input-sm input-sm pull-right " name="status" id="status">
                                        <option value="" @if($order->status == "") selected="selected"@endif>Select</option>
                                        <option value="Pending" @if($order->status == "Placed") selected="selected"@endif>Placed</option>
                                        <option value="Ordered" @if($order->status == "Parked") selected="selected"@endif>Parked</option>
                                        <option value="Recieved" @if($order->status == "Recieved") selected="selected"@endif>Recieved</option>
                                    </select>
                                </div>
                                <!-- /.input group -->
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>PAN NO:</label>
                                    <div class="date">
                                        {{-- <div class="input-group-addon">
                                            <i class="fa fa-file-code-o"></i>
                                        </div> --}}
                                        <div class="date">
                                            <input type="text" class="form-control input-sm input-sm pull-right " name="pan_no" value="{{ $order->pan_no }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Bill NO:</label>
                                    <div class="date">
                                        {{-- <div class="input-group-addon">
                                            <i class="fa fa-star"></i>
                                        </div> --}}
                                        <div class="input-group">
                                            <input type="text" class="form-control input-sm input-sm pull-right " name="bill_no" value="{{ $order->bill_no }}" id="bill_no">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Vat:</label>
                                    <select type="text" class="form-control input-sm input-sm pull-right " name="vat_type" id="vat_type">
                                        <option value="" @if($order->vat_type == "") selected="selected"@endif>Select</option>
                                        <option value="no" @if($order->vat_type == "no") selected="selected"@endif>No</option>
                                        <option value="yes" @if($order->vat_type == "yes") selected="selected"@endif>Yes(13%)</option>
                                    </select>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Is renewal:</label>
                                    <select type="text" class="form-control input-sm input-sm pull-right " name="is_renewal" id="is_renewal">
                                        <option value="0">No</option>
                                        <option value="1" @if($order->is_renewal == '1') selected @endif>Yes</option>
                                    </select>
                                </div>
                                <!-- /.input group -->
                            </div>

                            <div class="col-md-2 form-group">
                                <label >Outlets</label>
                                    <select name="into_stock_location" class="form-control input-sm searchable" id="location">

                                        @foreach($productlocation as $key=>$out)
                                            <option value="{{ $out->id }}" @if( $out->id ==  $order->into_stock_location) selected="" @endif>
                                                 {{$out->name}}
                                            </option>
                                        @endforeach

                                    </select>
                            </div>
                        </div>    
                           

                        <div class="col-md-12">
                            
                            <div class="col-md-2 form-group" style="">
                                <label for="user_id">Purchase Owner</label>
                                {!! Form::select('user_id', $users, \Auth::user()->user_id, ['class' => 'form-control input-sm input-sm', 'id'=>'user_id']) !!}
                            </div>

                            <div class="col-md-2 form-group" style="">
                                <label for="comment">Reference</label>
                                <input type="text" name="reference" id="reference" class="form-control input-sm input-sm" value="{{ $order->reference }}" readonly>
                            </div>

                            <input type="hidden" name="order_no" value="{{ $order->id }}">
                            @if(\Auth::user()->hasRole('admins'))
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Fiscal Year:</label>
                                    {!! Form::select('fiscal_year',$fiscal_years, $order->fiscal_year_id , ['class' => 'form-control input-sm','id'=>'fiscal_year']) !!}
                                </div>
                            </div>
                            @else
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Fiscal Year</label>
                                    <input type="text" readonly="" value="{{$order->get_fiscal_year->fiscal_year}}" class="form-control input-sm">
                                </div>
                            </div>
                            @endif

                                  <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Currency:</label>
                                         <select class="form-control input-sm select2 currency" name="currency" required="required">
                                                @foreach($currency as $k => $v)
                                                <option value="{{ $v->currency_code }}"

                                                @if($order->currency == $v->currency_code)
                                                selected=""
                                                @endif>
                                                    {{ $v->name }} {{ $v->currency_code }} ({{ $v->currency_symbol }})</option>
                                                @endforeach

                                            </select>
                                    </div>
                                </div>
                                <div class="col-md-2 import_show" @if($order->is_import==1) style="display: block" @else style="display:none"@endif>
                                    <div class="form-group">
                                        <label for="">Country</label>
                                        <input type="text" name="country" class="form-control" value="{{$order->country}}">
                                    </div>
                                </div>
                                <div class="col-md-2 import_show" @if($order->is_import==1) style="display: block" @else style="display:none"@endif>
                                    <div class="form-group">
                                        <label for="">Import Date</label>
                                        <div id='dateselectors3'>
                                            <input type="text" class="form-control input-sm pull-right datepicker date-toggle-nep-eng" name="import_date" value="{{$order->import_date}}" id="import_date" required="">
                                        </div>
                                    </div>
                                </div>
                        </div>    
                            <div class="col-md-12">
                                
                                
                                <div class="col-md-2 import_show" @if($order->is_import==1) style="display: block" @else style="display:none"@endif>
                                    <div class="form-group">
                                        <label for="">Document No</label>
                                        <input type="text" name="document_no" class="form-control" value="{{$order->document_no}}">
                                    </div>
                                </div>



                            </div>
                             <div class="col-md-12">



                            </div>


                        @if(\Request::get('type') && \Request::get('type') == 'purchase_orders')
                        <input type="hidden" name="purchase_type" value="purchase_orders">
                        @elseif(\Request::get('type') && \Request::get('type') == 'request')
                        <input type="hidden" name="purchase_type" value="request">
                        @elseif(\Request::get('type') && \Request::get('type') == 'bills')
                        <input type="hidden" name="purchase_type" value="bills">
                        @elseif(\Request::get('type') == 'assets')
                        <input type="hidden" name="purchase_type" value="assets">
                        @else
                        <input type="hidden" name="purchase_type" value="purchase_orders">
                        @endif


                        <!-- <div class="col-md-12">


                                 <div class="col-md-3 form-group" style="">
                                    <label for="comment">Status</label>
                                    {!! Form::select('status',['Active'=>'Active','Canceled'=>'Canceled','Invoiced'=>'Invoiced'],$order->status, ['class' => 'form-control input-sm input-sm', 'id'=>'user_id']) !!}
                                </div>

                            </div>  -->

                        <?php /**
                                @if(\Request::get('type') && \Request::get('type') == 'quotation')
                                    <input type="hidden" name="order_type" value="quotation">
                                @elseif(\Request::get('type') && \Request::get('type') == 'invoice')
                                    <input type="hidden" name="order_type" value="proforma_invoice">
                                @elseif(\Request::get('type') && \Request::get('type') == 'order')
                                    <input type="hidden" name="order_type" value="order">
                                @else
                                    <input type="hidden" name="order_type" value="quotation">
                                @endif
                                **/

                                ?>




                        <div class="clearfix"></div>

                        <div class="col-md-12">


                        </div>
                        <hr />
                        <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr class="bg-info tr-heading">
                                    <th style="width: 5px;">S.N</th>
                                    <th>Item*</th>
                                     <th class="col-sm-1">Qty*</th>
                                    <th class="col-sm-1">Rate*</th>
                                    <th class="col-sm-1">Discount</th>
                                    <th class="col-sm-1">Units</th>
                                    <th class="col-sm-1">Tax Rate</th>
                                     <th class="col-sm-1">TDS Rate</th> 
                                    <th>Total</th>
                                </tr>
                            </thead>

                            <tbody id='multipleDiv'>
                                <?php
                                $count=1;
                                    ?>
                                @foreach($orderDetails as $odk => $odv)
                                @if($odv->is_inventory == 1)
                                <tr>
                                    <td class='p_sn'>{{$count++}}</td>
                                    <td>
                                        <select class="form-control input-sm select2 product_id getid select-product-id" name="product_id[]" required="required">
                                            @if(isset($products))
                                            <option value="">Select Products</option>
                                            @foreach($products as $key => $pk)
                                            <option value="{{ $pk->id }}" @if($odv->product_id == $pk->id) selected="selected"@endif>{{ $pk->name }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </td>
                                     <td>
                                        <input type="number" class="form-control input-sm quantity" name="quantity[]" placeholder="Quantity"  value="{{ $odv->qty_invoiced }}" required="required" step=".01">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control input-sm price" name="price[]" placeholder="Price" value="{{ $odv->unit_price }}" required="required">
                                    </td>

                                    <td>
                                        <input type="number" name="dis_amount[]"
                                        class="form-control input-sm discount_amount_line" placeholder="Discount" step="any" value="{{ $odv->discount }}" step=".01">
                                    </td>

                                    <td>
                                        <select name='units[]' class="form-control input-sm units">
                                            <option value="">Unit</option>
                                            @foreach($prod_unit as $pu)
                                            <option value="{{ $pu->id }}" @if($odv->units == $pu->id) selected="" @endif>{{ $pu->symbol }}</option>
                                            @endforeach
                                        </select>
                                    </td>

                                    <td class="col-sm-1">
                                        <select class="form-control input-sm tax_rate_line" name="tax_type[]">
                                            <option value="0">Exempt(0)</option>
                                            <option value="13" @if($odv->tax_type_id == 13) selected=""
                                                @endif>VAT(13)</option>
                                        </select>
                                    </td>
                                     
                                    <td style="display: none;">

                                        <input type="number" class="form-control input-sm tax_amount_line"
                                        name="tax_amount[]"  value="{{ $odv->tax_amount }}" readonly="readonly">
                                    </td>
                                    {{-- dd{{$odv->tds_rate}} --}}
                                    <td>
                                        <input type="number" name="tds_per[]" value="{{$odv->tds_rate}}" step=".01" class="form-control input-sm tds_rate_line">
                                    </td>
                                    <td style="display: none;">
                                        <input type="number" name="tds_amount[]" value="{{$odv->tds_amount}}" step=".01" class="form-control input-sm tds_rate_amount">
                                    </td>    
                                    <td>
                                        <input type="number" class="form-control input-sm total" name="total[]" placeholder="Total" value="{{ $odv->total }}" readonly="readonly" style="float:left; width:85%;" step="any">
                                        <a href="javascript::void(1);" style="width: 5%;">
                                            <i class="remove-this icon fa fa-trash deletable" style="float: right; color: red;"></i>
                                        </a>
                                    </td>
                                </tr>
                                @elseif($odv->is_inventory == 0)

                                <tr>
                                    <td class='p_sn'></td>
                                    <td>

                                        <input type="text" class="form-control input-sm product input-sm" name="description_custom[]" value="{{ $odv->description }}" placeholder="Product">

                                    </td>
                                     <td>
                                        <input type="number" class="form-control input-sm quantity input-sm" name="quantity_custom[]" placeholder="Quantity" value="{{ $odv->qty_invoiced }}" required="required" step=".01">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control input-sm price input-sm" name="price_custom[]" placeholder="Rate" value="{{ $odv->unit_price }}" required="required">
                                    </td>
                                    <td>
                                        <input type="number" name="custom_dis_amount[]"
                                        class="form-control input-sm discount_amount_line input-sm" placeholder="Discount" step="any" value="{{ $odv->discount }}">
                                    </td>
                                    <td>
                                        <select name='custome_unit[]' class="form-control input-sm input-sm">
                                            <option value="">Unit</option>
                                            @foreach($prod_unit as $pu)
                                            <option value="{{ $pu->id }}" @if($odv->units == $pu->id) selected="" @endif>{{ $pu->name }}({{ $pu->symbol }})</option>
                                            @endforeach
                                        </select>
                                    </td>

                                     <td class="col-sm-1">
                                        <select class="form-control input-sm input-sm tax_rate_line"
                                        name="custome_tax_type[]">
                                            <option value="0">Exempt(0)</option>
                                            <option value="13" @if($odv->tax_type_id == 13) selected=""
                                                @endif>VAT(13)</option>
                                        </select>
                                    </td>

                                    <td>

                                        <input type="number" class="form-control input-sm tax_amount_line input-sm"
                                        name="custome_tax_amount[]"  value="{{ $odv->tax_amount }}" readonly="readonly" >
                                    </td>


                                    <!-- <td>
                                                <select required name="tax_custom[]" class="form-control input-sm tax_rate">
                                                    @foreach(config('tax.taxes') as $dk => $dv)
                                                    <option value="{{ $dk }}" @if(isset($odv->tax) && $odv->tax == $dk) selected="selected" @endif>{{ $dv }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control input-sm tax_amount" name="tax_amount_custom[]" @if(isset($odv->tax_amount)) value="{{ $odv->tax_amount }}" @else value="0" @endif readonly="readonly">
                                            </td> -->
                                    <td>
                                        <input type="number" class="form-control input-sm  input-sm total" name="total_custom[]" placeholder="Total" value="{{ $odv->total }}" readonly="readonly" style="float:left; width:70%;" step="any">
                                        <a href="javascript::void(1);" style="width: 10%;">
                                            <i class="remove-this icon fa fa-trash deletable" style="float: right; color: red;"></i>
                                        </a>
                                    </td>
                                </tr>

                                @endif
                                @endforeach
                                <tr class="multipleDiv"></tr>

                            </tbody>

                            <tfoot>
                                <tr>
                                    <td colspan="3">
                                        <a href="/admin/products/create" data-toggle="modal" data-target="#modal_dialog" class="btn btn-default btn-sm" style="float: left; display: none;" title="Create a new Product" id='addmorProducts'>
                                        <i class="fa fa-plus"></i> <span>Create New Product</span>
                                        </a>
                                    </td>
                                    <td colspan="6">
                                        <div class="btn-group pull-right">

                                            <a href="javascript::void(0)" class="btn btn-default btn-sm" id="addMore" >
                                                <i class="fa fa-plus"></i> <span>Add Products Item</span>
                                            </a> &nbsp;
                                            <a href="javascript::void(0)" class="btn btn-default btn-sm" id="addCustomMore" style="float:right;"  title='Inventory is not updated with custome product' >
                                                <i class="fa fa-plus"></i> <span>Add Custom Products Item</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="8" style="text-align: right;">Subtotal</td>
                                    <td id="sub-total">{{ $order->subtotal }}</td>
                                    <td>&nbsp; <input type="hidden" name="subtotal" id="subtotal" value="{{ $order->subtotal }}"></td>
                                </tr>

                                <tr>
                                    <td colspan="8" style="text-align: right;">Order Discount</td>
                                    <td id='discount-amount'>{{ $order->discount_amount }}</td>
                                    <td> <input type="hidden" name="discount_amount" value="0"
                                        id='discount_amount' value="{{ $order->discount_amount }}"> </td>
                                </tr>

                                  <tr>
                                    <td colspan="8" style="text-align: right;">Non Taxable Amount</td>
                                    <td id="non-taxable-amount">{{ $order->non_taxable_amount }}</td>
                                    <td>&nbsp;<input type="hidden" name="non_taxable_amount"
                                        id="nontaxableamount" value="{{ $order->non_taxable_amount }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="8" style="text-align: right;">Taxable Amount</td>
                                    <td id="taxable-amount">{{ $order->taxable_amount }}</td>
                                    <td>&nbsp;
                                        <input type="hidden" name="taxable_amount" id="taxableamount" value="{{ $order->taxable_amount }}"></td>
                                </tr>
                                <tr>
                                    <td colspan="8" style="text-align: right;">Tax Amount </td>
                                    <td id="taxable-tax">{{ $order->tax_amount }}</td>
                                    <td>&nbsp; <input type="hidden" name="taxable_tax" id="taxabletax" value="{{ $order->tax_amount }}"></td>
                                </tr>

                                <tr>
                                    <td colspan="8" style="text-align: right;"><strong>TOTAL</strong></td>
                                    <td id="total">{{ $order->total }}</td>
                                    <td>
                                        <input type="hidden" name="total_tax_amount" id="total_tax_amount" value="{{ $order->tax_amount }}">
                                        <input type="hidden" name="final_total" id="total_" value="{{ $order->total }}">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="8" style="text-align: right; font-weight: bold;">TDS</td>
                                    <td id="tdstotal">{{$order->tds_amount}}</td>
                                    <td>
                                        <input type="hidden" name="tds_total" id="tds_total" value="{{$order->tds_amount}}">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="8" style="text-align: right; font-weight: bold;">Net Payable</td>
                                    <td id="netreceivable">{{$order->netpayable}}</td>
                                    <td>
                                        <input type="hidden" name="netpayable" id="net_receivable" value="{{ $order->netpayable}}">
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="table-responsive import_show" style="display: none">
                        <table class="table table-striped">
                            <thead>
                                <tr class="bg-info tr-heading">
                                    <th  style="width: 4%;">S.N</th>
                                    <th  style="width: 16%;">Cost Term</th>
                                    <th  style="width: 20%;">Product</th>
                                    <th  style="width: 8%;">Methods</th>
                                    <th  style="width: 8%;">Amount</th>
                                    <th  style="width: 12%;">Debit Account</th>
                                    <th  style="width: 12%;">Credit Account</th>
                                    <th  style="width: 20%;">Description</th>
                                </tr>
                            </thead>
                            
                            <tbody id='multipleDivAddtionalCost'>
                               
                               
                                    @php
                                        $importcosts=\App\Models\ImportPurchase::where('purchase_order_id',$order->id)->get();
                                        $count=1;
                                    @endphp
                                @foreach($importcosts as $items)
                               
                                <tr>
                                    <td class='c_sn'>{{$count++}}</td>
                                    <td>

                                        <select class="form-control input-sm select2 cost_type cost_term_type" name="cost_type[]" required="required" >
                                            <option value="">Select Cost</option>
                                            @foreach ($costterms as $term)
                                                <option value="{{$term}}"  @if($items->cost_type==$term) selected @endif>{{$term}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                  
                                    <td>
                                        <select class="form-control input-sm select2 product_id select-product-id" name="cost_product_id[]" required="required">

                                            <option value="">Select Product</option>
                                            <option value="ALL">ALL Products</option>
                                            <option value="None">None</option>
                                            @foreach($products as $key => $pk)
                                            <option value="{{ $pk->id }}" @if($items->product_id==$pk->id)  selected="selected"@endif>{{ $pk->name }} (In Stock {{StockHelper::getRemaingStocks($pk->id)}})</option>
                                            @endforeach

                                        </select>
                                    </td>
                                    <td class="col-sm-1">
                                        <select name='method[]' class="form-control input-sm input-sm method">
                                            <option value="">--SELECT Method--</option>
                                            <option value="Quantity" @if($items->method=="Quantity") selected @endif>Quantity</option>
                                            <option value="Value" @if($items->method=="Value") selected @endif>Value</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control input-sm amount input-sm" name="amount[]" placeholder="Amount" value="{{$items->amount}}" required="required" autocomplete="off">
                                    </td>
                                    <td>
                                        <select name='debit_account[]' class="form-control input-sm select2 debit_account">
                                            <option value="">--SELECT--</option>
                                            @foreach ($ledger_all as $ledgerid=>$ledgername)
                                            <option value="{{$ledgerid}}" @if($items->debit_account_ledger_id==$ledgerid) selected @endif>{{$ledgername}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name='credit_account[]' class="form-control input-sm select2 credit_account">
                                            <option value="">--SELECT--</option>
                                            @foreach ($ledger_all as $ledgerid=>$ledgername)
                                            <option value="{{$ledgerid}}" @if($items->credit_account_ledger_id==$ledgerid) selected @endif>{{$ledgername}}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="col-sm-1">
                                        <input type="text" name="description[]" class="form-control input-sm description input-sm" placeholder="Description" value="{{$items->description}}" style="width: 80% !important;">

                                        <a href="javascript::void(1);" style="width: 10%;">
                                            <i class="remove-this icon fa fa-trash deletable" style="float: right; color: red;"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            
                                <tr class="multipleDivAddtionalCost"></tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3">

                                    </td>
                                    <td colspan="6">
                                        <div class="btn-group pull-right">
                                            <a href="javascript:void(0);" class="btn btn-default btn-sm" id="addMoreCost" style="float:right;" >
                                                <i class="fa fa-plus"></i> <span>Add Additonal Cost</span>
                                            </a> &nbsp;

                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                        <div class="col-md-6 form-group" style="margin-top:5px;">
                            <label for="comment">Narration</label>
                            <textarea class="form-control input-sm TextBox comment" name="comments">@if(isset($order)){{ $order->comments}}@endif</textarea>
                        </div>

                        <div class="col-md-6 form-group" style="margin-top:5px;">
                            <label for="address">Address</label>
                            <textarea class="form-control input-sm TextBox address" name="address">{{ $order->address }}</textarea>
                        </div>




                    </div>
                    <div class="panel-footer">
                        <button type="button" class="btn btn-social btn-foursquare PurchaseSubmitButton"  onclick="checkvalidation();" style="float: right;    margin-top: -10px;">
                            <i class="fa fa-save"></i>Save Order
                        </button>
                        <a href="/admin/purchase?type={{\Request::get('type')}}" class="btn btn-default" style="float: right;position: relative;
                        left: -15px; margin-top: -10px;">Close</a>
                    </div>
                    </form>
                </div>
            </div>

        </div><!-- /.box-body -->
    </div><!-- /.col -->

</div><!-- /.row -->
@endsection
<div class='supplier_options' style="display: none;">
    <div id='_supplier'>
        <option value="">Select Supplier</option>
        @if(isset($clients))
        @foreach($clients as $key => $uk)
        <option value="{{ $uk->id }}" @if($order && $uk->id == $order->supplier_id){{ 'selected="selected"' }}@endif>{{ '('.$uk->id.') '.$uk->name.' ('.$uk->vat.')' }}</option>
        @endforeach
        @endif
    </div>
    <div id='_paid_through'>
        <option value="">Select Supplier</option>
        @if(isset($clients))
        @foreach($paid_through as $key => $uk)
        <option value="{{ $uk->id }}" @if($order && $uk->id == $order->supplier_id){{ 'selected="selected"' }}@endif>{{ '('.$uk->id.') '.$uk->name.' ('.$uk->location.')' }}</option>
        @endforeach
        @endif
    </div>
</div>
@section('body_bottom')
<!-- form submit -->
@include('partials._date-toggle')
@include('partials._body_bottom_submit_bug_edit_form_js')
<script type="text/javascript">
    $( document ).ready(function(){
       ShowImportHideDiv(is_import);
    });
    function ShowImportHideDiv(is_import) {
        var import_show = document.getElementsByClassName("import_show");
        for (var i=0;i<import_show.length;i+=1){
            import_show[i].style.display = is_import.checked ? "block" : "none";
        }
    }
</script>
<script>
    var a= document.getElementsByClassName('cost_term_type');
    console.log(a);
</script>

<script>
    $('.date-toggle-nep-eng').nepalidatetoggle();
    const dateRange = {
        <?php $currentFiscalyear = FinanceHelper::cur_fisc_yr();?>
        minDate: `{{ $currentFiscalyear->start_date }}`,
        maxDate: `{{ $currentFiscalyear->end_date }}`
    }
    @if(\Request::get('type') == 'bills' || \Request::get('type') == 'assets')
    $('select[name=customer_id]').prop('required', true);
    @endif

    $('.customer_id').select2({


    });
    function adjustTotalNonTaxable(){


        var  taxableAmount = 0;

        var nontaxableAmount = 0;

        var taxAmount = 0;



        var taxableAmount = 0;

        var nontaxableAmount = 0;
        // let tds_rate = Number(parent.find('.tds_rate_line').val());

        $('.tax_rate_line').each(function(){

            let parent = $(this).parent().parent();

            let tax_rate = Number(parent.find('.tax_amount_line').val());
            var total = Number(parent.find('.total').val());

            if($(this).val() == 0 ){

                nontaxableAmount += total ;

            }else{

                taxableAmount +=  (Number(parent.find('.price').val())* Number(parent.find('.quantity').val()))- Number(parent.find('.discount_amount_line').val());

                taxAmount += tax_rate;
            }

        });
        
        $('#non-taxable-amount').text(nontaxableAmount.toLocaleString());

        $('#nontaxableamount').val(nontaxableAmount);

        $('#taxable-amount').text((taxableAmount ).toFixed(2).toLocaleString());

        $('#taxableamount').val((taxableAmount ).toFixed(2));

        $('#taxabletax').val(taxAmount.toFixed(2));

        $('#taxable-tax').text(taxAmount.toFixed(2).toLocaleString());

        var totalDiscount = 0;
        $('.discount_amount_line').each(function(){

            totalDiscount += Number($(this).val());


        });
        $('#discount-amount').text(totalDiscount);
        $('#discount_amount').val(totalDiscount);

    }

    function adjustTax(ev){

        let parent = ev.parent().parent();
        
        let total = Number(parent.find('.total').val());

        let discount = Number(parent.find('.discount_amount_line').val());
        let total_with_discount = total - discount;

        parent.find('.total').val(total_with_discount);

        let tax_rate = Number(parent.find('.tax_rate_line').val());

        let tax_amount = (tax_rate / 100 * total_with_discount);
        parent.find('.tax_amount_line').val(tax_amount.toFixed(2));

        let amount_with_tax = total_with_discount;

        parent.find('.total').val(amount_with_tax);
        let tds_rate = Number(parent.find('.tds_rate_line').val());

        let tds_amount = (tds_rate /100 * total_with_discount);
        parent.find('.tds_rate_amount').val(tds_amount.toFixed(2));
    }


    $(document).on('change','.tax_rate_line',function(){



        let parent = $(this).parent().parent();

        parent.find('.quantity').trigger('change');


    });

    $(document).on('change','.discount_amount_line',function(){

        let parent = $(this).parent().parent();

        parent.find('.quantity').trigger('change');

    })
    $(document).ready(function(){
        $('.select-product-id').select2({
            width:"100%"
        })
    })

    function isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }


    $(document).on('change', '.product_id', function() {
        var parentDiv = $(this).parent().parent();
        if (this.value != 'NULL') {
            var _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "POST"
                , contentType: "application/json; charset=utf-8"
                , url: "/admin/products/GetProductDetailAjax/" + this.value + '?_token=' + _token
                , success: function(result) {
                    var obj = jQuery.parseJSON(result.data);
                    parentDiv.find('.price').val(obj.cost);
                    parentDiv.find('.units').val("");
                    parentDiv.find('.units').val(result.units?.id);
                    if (isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '') {
                        var total = parentDiv.find('.quantity').val() * obj.cost;
                    } else {
                        var total = obj.cost;
                    }

                    var tax = parentDiv.find('.tax_rate_line').val();
                    var discount=parentDiv.find('.discount_amount_line').val();
                    if (isNumeric(tax) && tax != 0 && (total != 0 || total != '')) {
                        tax_amount = total * Number(tax) / 100;
                        parentDiv.find('.tax_amount_line').val(tax_amount);
                        total = total;
                    } else
                    parentDiv.find('.tax_amount_line').val('0');

                    parentDiv.find('.total').val(total);
                    calcTotal();
                }
            });
        } else {
            parentDiv.find('.price').val('');
            parentDiv.find('.total').val('');
            parentDiv.find('.tax_amount_line').val('');
            calcTotal();
        }
    });
    $(document).on('change', '.cost_type', function() {
					var parentDiv = $(this).parent().parent();
					if (this.value != 'NULL') {
						var _token = $('meta[name="csrf-token"]').attr('content');
						$.ajax({
							type: "POST"
							, contentType: "application/json; charset=utf-8"
							, url: "/admin/costterm/getledgerid/" + this.value + '?_token=' + _token
							, success: function(result) {
								var obj = jQuery.parseJSON(result.data);
								console.log(obj.dr_ledger_id);
                                parentDiv.find('.debit_account').val(obj.dr_ledger_id).trigger('change');
								parentDiv.find('.credit_account').val(obj.cr_ledger_id).trigger('change');
							}
						});
					} 
				});
    $(document).on('change', '.customer_id', function() {
        if (this.value != '') {
            $(".quantity").each(function(index) {
                var parentDiv = $(this).parent().parent();
                if (isNumeric($(this).val()) && $(this).val() != '')
                    var total = $(this).val() * parentDiv.find('.price').val();
                else
                    var total = parentDiv.find('.price').val();

                var tax = parentDiv.find('.tax_rate').val();
                if (isNumeric(tax) && tax != 0 && (total != 0 || total != '')) {
                    tax_amount = total * Number(tax) / 100;
                    parentDiv.find('.tax_amount').val(tax_amount);
                    total = total + tax_amount;
                } else
                parentDiv.find('.tax_amount').val('0');

                if (isNumeric(total) && total != '') {
                    parentDiv.find('.total').val(total);
                    calcTotal();
                }
            });
        } else {
            $('.total').val('0');
            $('.tax_amount').val('0');
            calcTotal();
        }

        let supp_id = $(this).val();
        $.get('/admin/getpanno/' + supp_id, function(data, status) {
            $('#pan_no').val(data.pan_no);
            $('#physical_address').val(data.physical_address);


        });
    });

    $(document).on('change', '.quantity', function() {
        var parentDiv = $(this).parent().parent();

        if (isNumeric(this.value) && this.value != '') {
            if (isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '') {
                var total = parentDiv.find('.price').val() * this.value;
            } else
            var total = '';
        } else
        var total = '';

        var tax = parentDiv.find('.tax_rate_line').val();
        console.log(tax);
        if (isNumeric(tax) && tax != 0 && (total != 0 || total != '')) {
            tax_amount = total * Number(tax) / 100;
            parentDiv.find('.tax_amount_line').val(tax_amount);
            total = total + tax_amount;
        } else
        parentDiv.find('.tax_amount_line').val('0');

        parentDiv.find('.total').val(total);

        adjustTax($(this));
        calcTotal();
    });

    $(document).on('change', '.price', function() {

        var parentDiv = $(this).parent().parent();
        if (isNumeric(this.value) && this.value != '') {
            if (isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '') {
                var total = parentDiv.find('.quantity').val() * this.value;
            } else
            var total = '';
        } else
        var total = '';

        var tax = parentDiv.find('.tax_rate_line').val();
        if (isNumeric(tax) && tax != 0 && (total != 0 || total != '')) {
            tax_amount = total * Number(tax) / 100;
            parentDiv.find('.tax_amount_line').val(tax_amount);
            total = total + tax_amount;
        } else
        parentDiv.find('.tax_amount_line').val('0');

        parentDiv.find('.total').val(total);
        adjustTax($(this));
        calcTotal();
    });

    $(document).on('change', '.total', function() {

        var parentDiv = $(this).parent().parent();
        if (isNumeric(this.value) && this.value != '') {
            var total = this.value;
            if (isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '') {
                var price = total / parentDiv.find('.quantity').val();
            } else
            var price = '';
        } else
        var price = '';

        var tax = parentDiv.find('.tax_rate_line').val();
        if (isNumeric(tax) && tax != 0 && (total != 0 || total != '')) {
            tax_amount = total * Number(tax) / 100;
            parentDiv.find('.tax_amount_line').val(tax_amount);
            total = total + tax_amount - discount;
        } else
        parentDiv.find('.tax_amount_line').val('0');

        parentDiv.find('.price').val(price);
        adjustTax($(this));
        calcTotal();
    });

    $(document).on('change', '.tax_rate_line', function() {
        var parentDiv = $(this).parent().parent();

        if (isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '') {
            var total = parentDiv.find('.price').val() * Number(parentDiv.find('.quantity').val());
        } else
        var total = '';

        var tax = $(this).val();
        var discount=parentDiv.find('.discount_amount_line').val();
        if (isNumeric(tax) && tax != 0 && (total != 0 || total != '')) {
            tax_amount = (total-discount) * Number(tax) / 100;
            parentDiv.find('.tax_amount_line').val(tax_amount);
            total = total-discount;
            // + tax_amount
        } else
        parentDiv.find('.tax_amount_line').val('0');

        parentDiv.find('.total').val(total);
        calcTotal();
    });
    $(document).on('change', '.tds_rate_line', function() {
        var parentDiv = $(this).parent().parent();

        if (isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '') {
            var total = parentDiv.find('.price').val() * Number(parentDiv.find('.quantity').val());
        } else
        var total = '';

        var tds = $(this).val();
        var discount=parentDiv.find('.discount_amount_line').val();
        if (isNumeric(tds) && (total != 0 || total != '')) {
            tds_amount = (total-discount) * Number(tds) / 100;
            parentDiv.find('.tds_rate_amount').val(tds_amount);
            total = total-discount;
        } else
        parentDiv.find('.tds_rate_amount').val('0');

        parentDiv.find('.total').val(total);
        calcTotal();
    });



    function getSn(){

        $('#multipleDiv tr').each(function(index,val){

            if(index > 0){
                $(this).find('.p_sn').html(index);
            }

        });
    }
    function getCsn(){
        $('#multipleDivAddtionalCost tr').each(function(index,val){

        if(index > 0){
            $(this).find('.c_sn').html(index);
        }

        });
    }

    $("#addMoreCost").on("click", function() {
					$(".multipleDivAddtionalCost").after($('#orderCostFields #more-tr').html());
					$(".multipleDivAddtionalCost").next('tr').find('.product_id').select2({
						width: '50%'
					});
					let pid =  $(".multipleDivAddtionalCost").next('tr').find('.product_id');
					pid.select2('destroy');
					pid.select2({
						width: '50%',
					});
                    $(".multipleDivAddtionalCost").next('tr').find('.debit_account').select2({
						width: '100%'
					});
					let did =  $(".multipleDivAddtionalCost").next('tr').find('.debit_account');
                    did.select2('destroy');
					did.select2({
						width: '100%',
					});
                    $(".multipleDivAddtionalCost").next('tr').find('.credit_account').select2({
						width: '100%'
					});
					let cid =  $(".multipleDivAddtionalCost").next('tr').find('.credit_account');
                    cid.select2('destroy');
					cid.select2({
						width: '100%',
					});
					$(".multipleDivAddtionalCost").next('tr').find('.quantity').val('1');

					getCsn();
					$('#addmorProducts').show(300);

				});
                $('.orderCostFields').on('click','.remove-this',function(){
                    $(this).parent().parent().parent().remove();
                    getCsn();
                })

				$("#addMore").on("click", function() {
					$(".multipleDiv").after($('#orderFields #more-tr').html());
					$(".multipleDiv").next('tr').find('.select-product-id-2').select2({
						width: '100%'
					});
					let pid =  $(".multipleDiv").next('tr').find('.select-product-id-2');
					pid.select2('destroy');
					pid.select2({
						width: '100%',
					});
					$(".multipleDiv").next('tr').find('.quantity').val('1');

					getSn();
					$('#addmorProducts').show(300);

				});
    $("#addCustomMore").on("click", function() {
        $(".multipleDiv").after($('#CustomOrderFields #more-custom-tr').html());
        $(".multipleDiv").next('tr').find('.quantity').val('1');
        getSn();
    });

    $(document).on('click', '.remove-this', function() {
        $(this).parent().parent().parent().remove();
        calcTotal();
        $("#multipleDiv .product_id").length > 0 ? $('#addmorProducts').show(300) : $('#addmorProducts').hide(300);
        getSn();
    });

    $(document).on('change', '#vat_type', function() {
        calcTotal();
    });

    function calcTotal() {

        adjustTotalNonTaxable();
        var subTotal = 0;
        var taxableAmount = 0;

        var total = 0;
        var tax_amount = 0;
        var taxableTax = 0;
        var tds= 0;
        var netreceivable=0;
        $(".total").each(function(index) {
            if (isNumeric($(this).val()))
                subTotal = Number(subTotal) + Number($(this).val());
        });
        $(".tax_amount").each(function(index) {
            if (isNumeric($(this).val()))
                tax_amount = Number(tax_amount) + Number($(this).val());
        });

        $('#sub-total').html(subTotal.toLocaleString());
        $('#subtotal').val(subTotal);


        total = Number( $('#nontaxableamount').val() ) + Number( $("#taxableamount").val() ) +
        Number($('#taxabletax').val());

        var discount_amount = $('#discount_amount').val();

        var vat_type = $('#vat_type').val();

        console.log(discount_amount);

        $('#total_tax_amount').val(tax_amount);

        $('#total').html(total.toLocaleString());
        $('#total_').val(total);

        $(".tds_rate_amount").each(function(index) {
            if (isNumeric($(this).val()))
                tds = Number(tds) + Number($(this).val());
        });	
        $('#tdstotal').html(tds.toLocaleString());
        $('#tds_total').val(tds);

        netreceivable= total - Number( $("#tds_total").val()) ;
        $('#netreceivable').html(netreceivable.toLocaleString());
        $('#net_receivable').val(netreceivable);

    }

    $(document).on('keyup', '#discount_amount', function() {
        calcTotal();
    });

</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.project_id').select2();

    });

</script>

<script type="text/javascript">
    $(function() {
        $('.datepicker').datetimepicker({
            format: 'YYYY-MM-DD'
            , sideBySide: true
            , allowInputToggle: true,

        });

    });

</script>

<script type="text/javascript">
    var refNo = 'PO-' + $("#reference_no").val();

    $("#reference_no_write").val(refNo);

    $(document).on('keyup', '#reference_no', function() {

        var val = $(this).val();

        if (val == null || val == '') {
            $("#errMsg").html("Already Exists");
            $('#btnSubmit').attr('disabled', 'disabled');
            return;
        } else {
            $('#btnSubmit').removeAttr('disabled');
        }

        var ref = 'PO-' + $(this).val();
        $("#reference_no_write").val(ref);
        $.ajax({
            method: "POST"
            , url: "/admin/purchase/reference-validation"
            , data: {
                "ref": ref
                , "_token": token
            }
        })
        .done(function(data) {
            var data = jQuery.parseJSON(data);
            if (data.status_no == 1) {
                $("#errMsg").html('Already Exists!');
            } else if (data.status_no == 0) {
                $("#errMsg").html('Available');
            }
        });
    });

    function openwindow() {
        var win = window.open('/admin/clients/modals?relation_type=supplier', '_blank', 'toolbar=yes, scrollbars=yes, resizable=yes, top=500,left=500,width=600, height=650');
    }

    function HandlePopupResult(result) {
        if (result) {
            let clients = result.clients;
            let types = $(`input[name=source]:checked`).val();
            if (types == 'lead') {
                lead_clients = clients;
            } else {
                customer_clients = clients;
            }
            var option = '<option value="">Select Supplier</option>';
            for (let c of clients) {
                option = option + `<option value='${c.id}'>${c.name}</option>`;
            }
            $('#customers_id select').html(option);
            $('.supplier_options #_supplier').html(option);
            setTimeout(function() {
                $('.customer_id').select2('destroy');
                $('#customers_id select').val(result.lastcreated);
                $('#pan_no').val(result.pan_no);
                $("#ajax_status").after("<span style='color:green;' id='status_update'>client sucessfully created</span>");
                $('#status_update').delay(3000).fadeOut('slow');
                $('.customer_id').select2();
            }, 500);
        } else {
            $("#ajax_status").after("<span style='color:red;' id='status_update'>failed to create clients</span>");
            $('#status_update').delay(3000).fadeOut('slow');
        }
    }

    $(document).on('hidden.bs.modal', '#modal_dialog', function(e) {
        $('#modal_dialog .modal-content').html('');
    });

    function handleProductModel(result) {
        var lastcreated = result.lastcreated;
        $('#modal_dialog').modal('hide');
        $('select.product_id').each(function() {
            let options = `<option value='${lastcreated.id}'>${lastcreated.name}</option>`
            $(this).append(options);
        });
        setTimeout(function() {
            alert("Product SuccessFully Added");
        }, 500);

    }

    $('#discount_type').change(function() {
        if ($(this).val() == 'a') {
            $('.discount_type').text('Order Discount (Amount)')
        } else {
            $('.discount_type').text('Order Discount (%)')
        }
        $('#discount_amount').val('')
        calcTotal();

    });
    $("input[name=supplier_type]").change(function() {
        if (!$(this).is(":checked")) {
            return;
        }
        $('.customer_id').select2('destroy');
        if ($(this).val() == 'supplier') {
            let option = $('.supplier_options #_supplier').html();
            $('select[name=customer_id]').html(option);
            $('#create_supplier').show();
        } else {
            let option = $('.supplier_options #_paid_through').html();
            $('select[name=customer_id]').html(option);
            $('#create_supplier').hide();
        }
        $('.customer_id').select2();

    });
    $('input[name=supplier_type]').trigger('change');


    $('#selectdatetype').val('nep');


    $('#selectdatetype').trigger('change');

</script>
{{-- Bar code scanner --}}
<script type="text/javascript">
    var keybuffer = '';

    function press(event) {
        if (event.which === 13) {
            bar_val = Number(keybuffer);
            keybuffer = '';


            console.log('Reading from Barcode');
            let prevItem = $(`#prod-${bar_val}`);


            if(prevItem.length > 0){


                let prevQty = prevItem.find('.quantity');
                let newQty = Number(prevQty.val()) + 1;

                prevQty.val(newQty);
                let pid =  prevItem.find('.product_id');
                product_detail(pid);
                return;
            }


            $(".multipleDiv").after($('#orderFields #more-tr').html());
            $(".multipleDiv").next('tr').find('.product_id').select2({
                width: '100%'
            });

            $(".multipleDiv").next('tr').attr('id',`prod-${bar_val}`);

            let pid =  $(".multipleDiv").next('tr').find('.product_id');
            pid.select2('destroy');
            pid.val(bar_val);
            pid.select2({
                width: '100%',
            });
            $(".multipleDiv").next('tr').find('.quantity').val('1');

            getSn();
            product_detail(pid);
        }
        var number = event.which - 48;
        if (number < 0 || number > 9) {
            return;
        }
        keybuffer += number;
    }

    $(document).on("keypress", press);

    function product_detail(pid){
        var parentDiv = $(pid).parent().parent();
        if (pid.val() != 'NULL') {
            var _token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: "POST"
                , contentType: "application/json; charset=utf-8"
                , url: "/admin/products/GetProductDetailAjax/" + pid.val() + '?_token=' + _token
                , success: function(result) {
                    var obj = jQuery.parseJSON(result.data);
                    parentDiv.find('.price').val(obj.price);
                    parentDiv.find('.units').val("");
                    parentDiv.find('.units').val(result.units?.id);
                    if (isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '') {
                        var total = parentDiv.find('.quantity').val() * obj.price;
                    } else {
                        var total = obj.price;
                    }

                    var tax = parentDiv.find('.tax_rate').val();
                    if (isNumeric(tax) && tax != 0 && (total != 0 || total != '')) {
                        tax_amount = total * Number(tax) / 100;
                        parentDiv.find('.tax_amount').val(tax_amount);
                        total = total + tax_amount;
                    } else
                    parentDiv.find('.tax_amount').val('0');

                    parentDiv.find('.total').val(total);
                    calcTotal();
                }
            });
        } else {
            parentDiv.find('.price').val('');
            parentDiv.find('.total').val('');
            parentDiv.find('.tax_amount').val('');
            calcTotal();
        }
    }
    async function checkvalidation(){
        const oldHtml = $('.PurchaseSubmitButton').html()
        var checkcustomer=document.getElementById('customers').value;
		var checkdate= document.getElementById('bill_date').value;
		var checkbill=document.getElementById('bill_no').value;
		var checklocation=document.getElementById('location').value;

		if(checkcustomer == null || checkcustomer == ''){
			alert('Please Select Customer Name');
			return false;
		}
		else if(checkdate == null || checkdate == '')
		{
			alert('Please Select Supplier Date');
			return false;
		}
		else if(checkbill == null || checkbill == '')
		{
			alert('Please Enter Bill number');
			return false;
		}
		else if(checklocation == null || checkdate == '')
		{
			alert('Please Select Location');
			return false;
		}
        const collection = document.getElementsByClassName("getid");

        if(collection.length<=1)
        {
            alert('Select Product Please');
            return false;
        }
        $('.PurchaseSubmitButton').html('<i class="fa fa-spinner fa-spin"></i> Processing')
        for (let i = 1; i < collection.length; i++)
        {
            const response = await $.get(`/admin/purchase/AjaxValidation/${collection[i].value}`)
            if (response.flags == false) {
                alert(`please link product type master and product division for ${response.name}`)
                $('.PurchaseSubmitButton').html(oldHtml)
                return false;
            }
        }
        console.log($('#mypurchaseform'))
        $('#mypurchaseform').submit()
        return true;
    }

</script>
@endsection
