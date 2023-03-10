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
            height:100%;
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

        .footer {
           position: fixed;
           left: 0;
           bottom: 0;
           width: 100%;
           background-color: #efefef;
           color: white;
           text-align: center;
        }

        
    </style>
@endsection

@section('content')
<link href="/bower_components/admin-lte/select2/css/select2.min.css" rel="stylesheet" />
<script src="/bower_components/admin-lte/select2/js/select2.min.js"></script>

<link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet" type="text/css" />

<section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
            <h1>
                {{ $page_title ?? "Page Title" }}
                <small>
                   {{$page_description ?? "Page Description"}}
                </small>
            </h1>
            {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
        </section>
    <div class='row'>
        <div class='col-md-12'>
            <div class="box box-body">
                <div id="orderFields" style="display: none;">
                    <table class="table">
                        <tbody id="more-tr">
                            <tr>  
                                <td>
                                  <select class="form-control select2 product_id" name="product_id[]" required="required">
                                       <option value="">Select Product</option>
                                      @foreach($products as $key => $pk)
                                          <option value="{{ $pk->id }}"@if(isset($orderDetail->product_id) && $orderDetail->product_id == $pk->id) selected="selected"@endif>{{ $pk->name }}</option>
                                      @endforeach
                                  </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control price" name="price[]" placeholder="Fare" value="@if(isset($orderDetail->price)){{ $orderDetail->price }}@endif" required="required">
                                </td>
                                <td>
                                    <input type="number" class="form-control quantity" name="quantity[]" placeholder="Quantity" min="1" value="1" required="required">
                                </td>
                                
                                <td>
                                    <input type="number" class="form-control total" name="total[]" placeholder="Total" value="@if(isset($orderDetail->total)){{ $orderDetail->total }}@endif" readonly="readonly" style="float:left; width:80%;">
                                    <a href="javascript::void(1);" style="width: 10%;">
                                        <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
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
                                <td>
                                  <input type="text" class="form-control product" name="custom_items_name[]" value="" placeholder="Product">
                                </td>
                                 
                                <td>
                                    <input type="text" class="form-control price" name="custom_items_price[]" placeholder="Price" value="@if(isset($orderDetail->price)){{ $orderDetail->price }}@endif" required="required">
                                </td>
                               
                                <td>
                                    <input type="number" class="form-control quantity" name="custom_items_qty[]" placeholder="Quantity" min="1" value="1" required="required">
                                </td>
                                 
                                <td>
                                    <input type="number" class="form-control total" name="custom_total[]" placeholder="Total" value="@if(isset($orderDetail->total)){{ $orderDetail->total }}@endif" readonly="readonly" style="float:left; width:80%;">
                                    <a href="javascript::void(1);" style="width: 10%;">
                                        <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12">
                    <div class="">
                        <form method="POST" action="{{route('admin.creditnote.store')}}">
                            {{ csrf_field() }}

                            <div class="">
                         

                             <div class="clearfix"></div>
                             <div class="col-md-12">
                                <div class="col-md-12">

                                    <div class="col-md-3 form-group" style="">
                                          <label for="comment">Sales Invoice Number</label>
                                          <input type="text" class="form-control pull-right" name="sales_invoice_number" value="" id="sales_invoice_number" required="">
                                    </div>


                                    <div class="col-md-3">
                                      {!! Form::label('customer', 'Customer') !!}                
                                       <input type="text" name="customer_name" value="" class="form-control pull-right" id="customer_name">
                                       <input type="hidden" name="customer_id" value="" id="client_id">
                                    </div>


                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>PAN Number:</label>
                                        <div class="input-group">
                                          <div class="input-group-addon">
                                            <i class="fa fa-file"></i>
                                          </div>
                                          <input type="text" name="customer_pan" value="{{ old('customer_pan')}}" class="form-control pull-right" id="pan_no" onKeyUp="if(this.value>999999999){this.value='999999999';}else if(this.value<0){this.value='0';}">
                                        </div>
                                        <!-- /.input group -->
                                      </div>
                                </div>


                                 <div class="col-md-3">
                                    <div class="form-group">
                                <label>CreditNote Date:</label>

                                <div class="input-group date">
                                  <div class="input-group-addon">
                                    <i class="fa fa-calendar-alt"></i>
                                  </div>
                                  <input type="text" class="form-control pull-right datepicker" name="bill_date" value="{{\Carbon\Carbon::now()->toDateString()}}" id="bill_date" required="">
                                </div>
                                <!-- /.input group -->
                              </div>
                            </div>  
                            </div> 


                               

                                </div>


                            <div class="col-md-12">




                                <div class="col-md-12" style="background-color: #efefef; padding-top: 5px;padding-bottom: 5px">

                                    <div class="col-md-3 form-group" style="">
                                        <label for="comment">Due Date</label>
                                          <input type="text" class="form-control pull-right datepicker" name="due_date" value="{{\Carbon\Carbon::now()->addDays(7)->toDateString()}}" id="due_date">
                                    </div>

                       

                                    
                               </div>

                       
                               

                                <div class="clearfix"></div><br/><br/>

                               <!--  <div class="col-md-12">
                                    <a href="javascript::void(0)" class="btn btn-default btn-xs" id="addMore" style="float: right;">
                                        <i class="fa fa-plus"></i> <span>Add Products Item</span>
                                    </a>
                                  &nbsp; &nbsp; &nbsp;
                                    <a href="javascript::void(0)" class="btn btn-default btn-xs" id="addCustomMore" style="float: right;">
                                        <i class="fa fa-plus"></i> <span> Add Custom Products Item</span>
                                    </a>
                                </div> -->


                                <hr/>
                                <table class="table">
                                    <thead>
                                        <tr class="bg-primary">
                                            <th>Item *</th>
                                            <th>Price *</th>
                                            <th>Quantity *</th>
                                            <th>Returnable</th>
                                            <th>Credit Qty</th>
                                            <th>Credit Price</th>
                                            <th>Line Total</th>
                                            <th>Reason</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr class="multipleDiv">
                                           
                                        </tr>
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <td colspan="7" style="text-align: right;">Amount</td>
                                            <td id="sub-total">0.00</td>
                                            <td>&nbsp; <input type="hidden" name="subtotal" id="subtotal" value="0"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="7" style="text-align: right;">Order Discount (%)</td>
                                            <td><input type="number" min="0" name="discount_percent" id="discount_amount" value="" onKeyUp="if(this.value>99){this.value='99';}else if(this.value<0){this.value='0';}" ></td>
                                            <td>&nbsp;</td>
                                        </tr>
                                       <!--  <tr>
                                            <td colspan="3" style="text-align: right;">Discount Note</td>
                                            <td>
                                                <input type="text" name="discount_note" id="discount_note" value="">
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr> -->
                                        <tr>
                                            <td colspan="7" style="text-align: right;">Taxable Amount</td>
                                            <td id="taxable-amount">0.00</td>
                                            <td>&nbsp; <input type="hidden" name="taxable_amount" id="taxableamount" value="0"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="7" style="text-align: right;">Tax Amount (13%)</td>
                                            <td id="taxable-tax">0.00</td>
                                            <td>&nbsp; <input type="hidden" name="taxable_tax" id="taxabletax" value="0"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="7" style="text-align: right; font-weight: bold;font-size: 16px">Total Amount  ({{ env('APP_CURRENCY')}}) </td>
                                            <td style="font-size: 16px;font-weight: bold;" id="total">0.00</td>
                                            <td>
                                                <input type="hidden" name="total_tax_amount" id="total_tax_amount" value="0">
                                                <input type="hidden" name="final_total" id="total_" value="0">
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>

                                <br/>

                                

                                <div class="col-md-6 form-group" style="margin-top:5px;">
                                    <label for="comment">Customer Notes</label>
                                    <textarea class="form-control TextBox comment" name="comment" id="comment">@if(isset($order->comment)){{ $order->comment }}@endif</textarea>
                                </div>

                                <div class="col-md-6 form-group" style="margin-top:5px;">
                                    <label for="address">Address</label>
                                    <textarea class="form-control TextBox address" name="address" id="address">@if(isset($orderDetail->address)){{ $orderDetail->address }}@endif</textarea>
                                </div>
                            </div>
                            <div class="panel-footer footer">
                                <button type="submit" class="btn btn-social btn-foursquare">
                                    <i class="fa fa-save"></i>Save Credit
                                </button>

                                <a class="btn btn-social btn-foursquare" href="/admin/creditnote"> <i class="fa fa-times"></i> Cancel </a>
                            </div>
                        </form>
                    </div>
                </div>

            </div><!-- /.box-body -->
        </div><!-- /.col -->

    </div><!-- /.row -->
@endsection

@section('body_bottom')
    <!-- form submit -->
    @include('partials._body_bottom_submit_bug_edit_form_js')

    <script type="text/javascript">

    // $(function() {
    //     $('.datepicker').datetimepicker({
    //       //inline: true,
    //       format: 'YYYY-MM-DD',
    //       sideBySide: true,
    //       allowInputToggle: true
    //     });

    //   });
  </script>

<script>

function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

$(document).on('change', '.product_id', function() {
    var parentDiv = $(this).parent().parent();
    if(this.value != 'NULL')
    {
        var _token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
              type: "POST",
              contentType: "application/json; charset=utf-8",
              url: "/admin/products/GetProductDetailAjax/"+this.value+'?_token='+_token,
              success: function (result) {
                var obj = jQuery.parseJSON(result.data);
                parentDiv.find('.price').val(obj.price);

                if(isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '')
                {
                    var total = parentDiv.find('.quantity').val() * obj.price;
                }
                else
                {
                    var total = obj.price;
                }

                var tax = parentDiv.find('.tax_rate').val();
                if(isNumeric(tax) && tax != 0 && (total != 0 || total != ''))
                {
                    tax_amount = total * Number(tax) / 100;
                    parentDiv.find('.tax_amount').val(tax_amount);
                    total = total + tax_amount;
                }
                else
                    parentDiv.find('.tax_amount').val('0');

                parentDiv.find('.total').val(total);
                calcTotal();
              }
         });
    }
    else
    {
        parentDiv.find('.price').val('');
        parentDiv.find('.total').val('');
        parentDiv.find('.tax_amount').val('');
        calcTotal();
    }
});

$(document).on('change', '.customer_id', function() {
    if(this.value != '')
    {
        $(".quantity").each(function(index) {
            var parentDiv = $(this).parent().parent();
            if(isNumeric($(this).val()) && $(this).val() != '')
                var total = $(this).val() * parentDiv.find('.price').val();
            else
                var total = parentDiv.find('.price').val();

            var tax = parentDiv.find('.tax_rate').val();
            if(isNumeric(tax) && tax != 0 && (total != 0 || total != ''))
            {
                tax_amount = total * Number(tax) / 100;
                parentDiv.find('.tax_amount').val(tax_amount);
                total = total + tax_amount;
            }
            else
                parentDiv.find('.tax_amount').val('0');

            if(isNumeric(total) && total != '')
            {
                parentDiv.find('.total').val(total);
                calcTotal();
            }
            //console.log( index + ": " + $(this).text() );
        });
    }
    else
    {
        $('.total').val('0');
        $('.tax_amount').val('0');
        calcTotal();
    }
let cust_id = $(this).val();
    $.get('/admin/getpanno/'+cust_id,function(data,status){
          $('#pan_no').val(data.pan_no);

    });
});

$(document).on('change', '.quantity', function() {
    var parentDiv = $(this).parent().parent();
    if(isNumeric(this.value) && this.value != '')
    {
        if(isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '')
        {
            var total = parentDiv.find('.price').val() * this.value;
        }
        else
            var total = '';
    }
    else
        var total = '';

    var tax = parentDiv.find('.tax_rate').val();
    if(isNumeric(tax) && tax != 0 && (total != 0 || total != ''))
    {
        tax_amount = total * Number(tax) / 100;
        parentDiv.find('.tax_amount').val(tax_amount);
        total = total + tax_amount;
    }
    else
        parentDiv.find('.tax_amount').val('0');

    parentDiv.find('.total').val(total);
    calcTotal();
});

$(document).on('change', '.price', function() {
    var parentDiv = $(this).parent().parent();
    if(isNumeric(this.value) && this.value != '')
    {
        if(isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '')
        {
            var total = parentDiv.find('.quantity').val() * this.value;
        }
        else
            var total = '';
    }
    else
        var total = '';

    var tax = parentDiv.find('.tax_rate').val();
    if(isNumeric(tax) && tax != 0 && (total != 0 || total != ''))
    {
        tax_amount = total * Number(tax) / 100;
        parentDiv.find('.tax_amount').val(tax_amount);
        total = total + tax_amount;
    }
    else
        parentDiv.find('.tax_amount').val('0');

    parentDiv.find('.total').val(total);
    calcTotal();
});

$(document).on('change', '.tax_rate', function() {
    var parentDiv = $(this).parent().parent();

    if(isNumeric(parentDiv.find('.quantity').val()) && parentDiv.find('.quantity').val() != '')
    {
        var total = parentDiv.find('.price').val() * Number(parentDiv.find('.quantity').val());
    }
    else
        var total = '';

    var tax = $(this).val();
    if(isNumeric(tax) && tax != 0 && (total != 0 || total != ''))
    {
        tax_amount = total * Number(tax) / 100;
        parentDiv.find('.tax_amount').val(tax_amount);
        total = total + tax_amount;
    }
    else
        parentDiv.find('.tax_amount').val('0');

    parentDiv.find('.total').val(total);
    calcTotal();
});

/*$('#discount').on('change', function() {
    if(isNumeric(this.value) && this.value != '')
    {
        if(isNumeric($('#sub-total').val()) && $('#sub-total').val() != '')
            parentDiv.find('.total').val($('#sub-total').val() - this.value).trigger('change');
    }
});

$("#sub-total").bind("change", function() {
    if(isNumeric($('#discount').val()) && $('#discount').val() != '')
        parentDiv.find('.total').val($('#sub-total').val() - $('#discount').val());
    else
        parentDiv.find('.total').val($('#sub-total').val());
});*/

$("#addMore").on("click", function () {
     //$($('#orderFields').html()).insertBefore(".multipleDiv");
     $(".multipleDiv").after($('#orderFields #more-tr').html());
    $(".multipleDiv").next('tr').find('select').select2({ width: '100%' }); 
});
$("#addCustomMore").on("click", function () {
     //$($('#orderFields').html()).insertBefore(".multipleDiv");
     $(".multipleDiv").after($('#CustomOrderFields #more-custom-tr').html());
});

$(document).on('click', '.remove-this', function () {
    $(this).parent().parent().parent().remove();
    calcTotal();
});

function calcTotal()
{
    //alert('hi');
    var subTotal = 0;
    var taxableAmount =0;

    //var tax = Number($('#tax').val().replace('%', ''));
    var total = 0;
    var tax_amount = 0;
    var taxableTax = 0;
    $(".total").each(function(index) {
        if(isNumeric($(this).val()))
            subTotal = Number(subTotal) + Number($(this).val());
    });
    $(".tax_amount").each(function(index) {
        if(isNumeric($(this).val()))
            tax_amount = Number(tax_amount) + Number($(this).val());
    });
    $('#sub-total').html(subTotal);
    $('#subtotal').val(subTotal);

    $('#taxable-amount').html(subTotal);
    $('#taxableamount').val(subTotal);

    var discount_amount = $('#discount_amount').val();

    if(isNumeric(discount_amount) && discount_amount != 0)
    {
        
        taxableAmount = subTotal - (Number(discount_amount)/100 * subTotal );

    }
    else
    {
        total = subTotal;
        taxableAmount = subTotal;
    }

    total = taxableAmount + Number(13/100 * taxableAmount );
    taxableTax =  Number(13/100 * taxableAmount );

  
    $('#taxableamount').val(taxableAmount);
    $('#taxable-amount').html(taxableAmount);

    $('#total_tax_amount').val(tax_amount);

    $('#taxabletax').val(taxableTax);
    $('#taxable-tax').html(taxableTax);

    $('#total').html(total);
    $('#total_').val(total);
}

$(document).on('keyup', '#discount_amount', function () {
    calcTotal();
});
</script>

<script type="text/javascript">
         $(document).ready(function() {
    $('.customer_id').select2();
});
</script>

<script type="text/javascript">
    $(function() {
        $('.datepicker').datetimepicker({
          //inline: true,
          format: 'YYYY-MM-DD',
          sideBySide: true,
          allowInputToggle: true
        });

      });
      function openwindow(){
       var win =  window.open('/admin/clients/modals', '_blank','toolbar=yes, scrollbars=yes, resizable=yes, top=500,left=500,width=600, height=650');
    //     var timer = setInterval(function() {
    //     if (win.closed) {
    //         clearInterval(timer);
    //         console.log("DONE");
    //     }
    // }, 500);
    }
    function HandlePopupResult(result) {
      if(result){
        let clients = result.clients;
        var option = '';
        for(let c of clients){
            option = option + `<option value='${c.id}'>${c.name}</option>`; 
        }
        $('#client_id').html(option);
        setTimeout(function(){
            $('#client_id').val(result.lastcreated);
            $("#ajax_status").after("<span style='color:green;' id='status_update'>Client sucessfully created</span>");
            $('#status_update').delay(3000).fadeOut('slow');
        },500);
      }
      else{
        $("#ajax_status").after("<span style='color:red;' id='status_update'>failed to create clients</span>");
        $('#status_update').delay(3000).fadeOut('slow');
      }
    }
</script>
<script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
<script type="text/javascript">
    
  $(document).ready(function() {
     
      $("#sales_invoice_number").autocomplete({
            source: "/admin/getInvoiceId",  
            minLength: 1
      });
    });

    $('#sales_invoice_number').on('change',function(){
         $.ajax(
            {
             url: "/admin/getInvoiceInfo",  
             data: { salesinvoice_id: $(this).val() }, 
             dataType: "json", 
                success: function( data ) { 
                    var invoiceinfo = data.invoiceinfo;
                    var invoicedetailinfo = data.invoicedetailinfo;
                    var customer_name = data.customer_name;
                    populateInvoiceInfo(invoiceinfo);
                    $('#customer_name').val(customer_name).prop("readonly", true); 
                     $(".multipleDiv").after(invoicedetailinfo);

                } 
            }); 

    });

    $(document).on('change', '.quantity', function() {

        var parentDiv = $(this).parent().parent();

        if(isNumeric(this.value) && this.value != ''){

            var invoice_qty = parentDiv.find('.invoice_quantity').val();
            if(this.value > invoice_qty){
                  $(this).val(parentDiv.find('.invoice_quantity').val()); 

                alert("Credit Quantity Cannot Be Greater Than Invoice Quantity"); 
               
            }    
        }
       
    });

    function populateInvoiceInfo(invoiceinfo){

        // $('#bill_date').val(invoiceinfo.bill_date).prop("readonly", true); 
        // $('#due_date').val(invoiceinfo.due_date).prop("readonly", true); 
        // $('#name').val(invoiceinfo.name).prop("readonly", true);
        // $('#position').val(invoiceinfo.position).prop("readonly", true); 
        $('#client_id').val(invoiceinfo.client_id); 
        $('#user_id').val(invoiceinfo.user_id).prop("readonly", true); 
        $('#location_id').val(invoiceinfo.from_stock_location); 
        $('#terms').val(invoiceinfo.terms); 
        $('#pan_no').val(invoiceinfo.pan_no).prop("readonly", true); 

        $('#comment').val(invoiceinfo.comment); 
        $('#address').val(invoiceinfo.address); 

        $('#location_id').val(invoiceinfo.from_stock_location); 
        $('#terms').val(invoiceinfo.terms); 

        $('#discount_amount').val(invoiceinfo.discount_percent);

        $('#subtotal').val(invoiceinfo.subtotal);
        $('#sub-total').html(invoiceinfo.subtotal);

        $('#taxableamount').val(invoiceinfo.taxable_amount);
        $('#taxable-amount').html(invoiceinfo.taxable_amount);

        $('#taxabletax').val(invoiceinfo.tax_amount);
        $('#taxable-tax').html(invoiceinfo.tax_amount);

        $('#total').html(invoiceinfo.total_amount);
        $('#total_').val(invoiceinfo.total_amount);
       
        return 0;
   }



</script>
@endsection
