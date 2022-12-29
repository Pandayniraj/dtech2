<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Product;
use App\Models\SupplierReturnDetail;
use App\ProductLocation;
use Excel;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

/**
FOR ONLINE ENQUIRY

 **/
class SupplierReturnController extends Controller
{
    public function index()
    {
        $purchasereturn = \App\Models\SupplierReturn::orderBy('id', 'desc')->paginate(20);

        $page_title = 'Admin | Supplier Return';
        $page_description = 'Manage Supplier Return';

        return view('admin.supplierreturn.index', compact('page_title', 'page_description', 'purchasereturn'));
    }

    public function create()
    {
        $page_title = 'Admin | Supplier Return | Create';
        $page_description = 'Creates Supplier Return';

        $products = Product::select('id', 'name')->get();
        $users = \App\User::where('enabled', '1')->where('org_id', Auth::user()->org_id)->pluck('first_name', 'id');
        $productlocation = \App\Models\ProductLocation::pluck('location_name', 'id')->all();
        $purchaseid=\App\Models\PurchaseOrder::select('id')->get();
        $clients = Client::select('id', 'name', 'location')->orderBy('id', 'DESC')->get();

        return view('admin.supplierreturn.create', compact('page_title', 'page_description', 'products', 'purchaseid','users', 'productlocation', 'clients'));
    }

    public function show($id)
    {
        $page_title = 'Show Supplier Return';

        $page_description = 'Detail of Return';

        $ord = \App\Models\SupplierReturn::find($id);
        $orderDetails = \App\Models\SupplierReturnDetail::where('supplier_return_id', $ord->id)->get();

        return view('admin.supplierreturn.show', compact('page_title', 'page_description', 'ord', 'orderDetails'));
    }

    public function store(Request $request)
    {
        $attributes = $request->all();
        $attributes['purchase_bill_id']= implode(",",$request->purchase_bill_id);
        $attributes['purchase_bill_no']= $request->purchase_bill_no[0];
        $attributes['purchase_order_date']= $request->purchase_order_date[0];
        $attributes['status']= $request->status[0];
        $attributes['pan_no']=$request->pan_no[0];
        $attributes['supplier_id'] = $request->client_id[0];
        $attributes['supplier_name'] = $request->client_name[0];
        $attributes['org_id'] = Auth::user()->org_id;
        $attributes['tax_amount'] = $request->taxable_tax;
        $attributes['total_amount'] = $request->final_total;
        $purchasereturn = \App\Models\SupplierReturn::create($attributes);

        $product_id = $request->product_id;
        $units = $request->units;
        $purchase_quantity = $request->purchase_quantity;
        $return_quantity = $request->return_quantity;
        $purchase_price = $request->purchase_price;
        $return_price = $request->return_price;
        $return_total = $request->return_total;
        $reason = $request->reason;

        foreach ($product_id as $key => $value) {
            if ($value != '') {
                $detail = new SupplierReturnDetail();
                $detail->supplier_return_id = $purchasereturn->id;
                $detail->product_id = $product_id[$key];
                $detail->units = $units[$key];
                $detail->purchase_quantity = $purchase_quantity[$key];
                $detail->return_quantity = $return_quantity[$key];
                $detail->purchase_price = $purchase_price[$key];
                $detail->return_price = $return_price[$key];
                $detail->return_total = $return_total[$key];
                $detail->reason = $reason[$key];
                $detail->is_inventory = 1;
                $detail->save();
            }
        }

        $custom_items_name = $request->custom_items_name;
        $custom_units = $request->custom_units;
        $custom_purchase_qty = $request->custom_purchase_qty;
        $custom_return_qty = $request->custom_return_qty;
        $custom_purchase_price = $request->custom_purchase_price;
        $custom_return_price = $request->custom_return_price;
        $custom_return_total = $request->custom_return_total;
        $custom_reason = $request->custom_reason;

        foreach ($custom_items_name as $key => $value) {
            if ($value != '') {
                $detail = new SupplierReturnDetail();
                $detail->supplier_return_id = $purchasereturn->id;
                $detail->description = $custom_items_name[$key];
                $detail->units = $custom_units[$key];
                $detail->purchase_quantity = $custom_purchase_qty[$key];
                $detail->return_quantity = $custom_return_qty[$key];
                $detail->purchase_price = $custom_purchase_price[$key];
                $detail->return_price = $custom_return_price[$key];
                $detail->return_total = $custom_return_total[$key];
                $detail->reason = $custom_reason[$key];
                $detail->is_inventory = 0;
                $detail->save();
            }
        }

        $this->updateEntries($purchasereturn->id);

        Flash::success('Supplier Return created Successfully.');

        return redirect('/admin/supplierreturn');
    }

    public function edit(Request $request, $id)
    {
        $page_title = 'Edit Supplier Return';

        $page_description = '';

        $purchasereturn = \App\Models\SupplierReturn::find($id);
        $purchase_return_detail = \App\Models\SupplierReturnDetail::where('supplier_return_id', $purchasereturn->id)->get();

        $products = Product::select('id', 'name')->get();
        $users = \App\User::where('enabled', '1')->where('org_id', Auth::user()->org_id)->pluck('first_name', 'id');
        $productlocation = \App\Models\ProductLocation::pluck('location_name', 'id')->all();
        $clients = Client::select('id', 'name', 'location')->orderBy('id', DESC)->get();

        return view('admin.supplierreturn.edit', compact('page_title', 'page_description', 'purchasereturn', 'purchase_return_detail', 'products', 'users', 'productlocation', 'clients'));
    }

    public function update(Request $request, $id)
    {
        $purchasereturn = \App\Models\SupplierReturn::find($id);

        $attributes = $request->all();
        $attributes['purchase_bill_id']= $request->purchase_bill_id;
        $attributes['purchase_bill_no']= $request->purchase_bill_no;
        $attributes['purchase_order_date']= $request->purchase_order_date;
        $attributes['status']= $request->status;
        $attributes['pan_no']=$request->pan_no;
        $attributes['supplier_id'] = $request->client_id;
        $attributes['supplier_name'] = $request->client_name;
        $attributes['org_id'] = Auth::user()->org_id;
        $attributes['tax_amount'] = $request->taxable_tax;
        $attributes['total_amount'] = $request->final_total;

        $purchasereturn->update($attributes);

        \App\Models\SupplierReturnDetail::where('supplier_return_id', $purchasereturn->id)->delete();

        $product_id = $request->product_id;
        $units = $request->units;
        $purchase_quantity = $request->purchase_quantity;
        $return_quantity = $request->return_quantity;
        $purchase_price = $request->purchase_price;
        $return_price = $request->return_price;
        $return_total = $request->return_total;
        $reason = $request->reason;

        foreach ($product_id as $key => $value) {
            if ($value != '') {
                $detail = new \App\Models\SupplierReturnDetail();
                $detail->supplier_return_id = $purchasereturn->id;
                $detail->product_id = $product_id[$key];
                $detail->units = $units[$key];
                $detail->purchase_quantity = $purchase_quantity[$key];
                $detail->return_quantity = $return_quantity[$key];
                $detail->purchase_price = $purchase_price[$key];
                $detail->return_price = $return_price[$key];
                $detail->return_total = $return_total[$key];
                $detail->reason = $reason[$key];
                $detail->is_inventory = 1;
                $detail->save();
            }
        }

        $custom_items_name = $request->custom_items_name;
        $custom_units = $request->custom_units;
        $custom_purchase_qty = $request->custom_purchase_qty;
        $custom_return_qty = $request->custom_return_qty;
        $custom_purchase_price = $request->custom_purchase_price;
        $custom_return_price = $request->custom_return_price;
        $custom_return_total = $request->custom_return_total;
        $custom_reason = $request->custom_reason;

        foreach ($custom_items_name as $key => $value) {
            if ($value != '') {
                $detail = new \App\Models\SupplierReturnDetail();
                $detail->supplier_return_id = $purchasereturn->id;
                $detail->description = $custom_items_name[$key];
                $detail->units = $custom_units[$key];
                $detail->purchase_quantity = $custom_purchase_qty[$key];
                $detail->return_quantity = $custom_return_qty[$key];
                $detail->purchase_price = $custom_purchase_price[$key];
                $detail->return_price = $custom_return_price[$key];
                $detail->return_total = $custom_return_total[$key];
                $detail->reason = $custom_reason[$key];
                $detail->is_inventory = 0;
                $detail->save();
            }
        }

        $this->updateEntries($id);

        Flash::success('Supplier Return Updated Successfully.');

        return redirect('/admin/supplierreturn');
    }

    public function pdf($id)
    {
        $ord = \App\Models\SupplierReturn::find($id);
        $orderDetails = \App\Models\SupplierReturnDetail::where('supplier_return_id', $ord->id)->get();
        $imagepath = Auth::user()->organization->logo;

        $pdf = \PDF::loadView('admin.supplierreturn.pdf', compact('ord', 'imagepath', 'orderDetails'));
        $file = $id.'_'.$ord->name.'_'.str_replace(' ', '_', $ord->supplier->name).'.pdf';

        if (File::exists('reports/'.$file)) {
            File::Delete('reports/'.$file);
        }

        return $pdf->download($file);
    }

    public function print($id)
    {
        $ord = \App\Models\SupplierReturn::find($id);
        $orderDetails = \App\Models\SupplierReturnDetail::where('supplier_return_id', $ord->id)->get();

        $imagepath = Auth::user()->organization->logo;

        return view('admin.supplierreturn.print', compact('ord', 'imagepath', 'orderDetails', 'print_no'));
    }

    public function destroy($id)
    {

        //dd($id);
        $ord = \App\Models\SupplierReturn::find($id);

        if (! $ord->isdeletable()) {
            abort(403);
        }

        if ($ord->entry_id && $ord->entry_id != '0') {
            $entries = \App\Models\Entry::find($ord->entry_id);
            \App\Models\Entryitem::where('entry_id', $entries->id)->delete();
            \App\Models\Entry::find($ord->entry_id)->delete();
        }

        \App\Models\SupplierReturn::find($id)->delete();
        \App\Models\SupplierReturnDetail::where('supplier_return_id', $id)->delete();

        Flash::success('Supplier Return successfully deleted.');

        return redirect('/admin/supplierreturn');
    }

    public function getModalDelete($id)
    {

        //dd($id);
        $error = null;

        $ord = \App\Models\SupplierReturn::find($id);

        if (! $ord->isdeletable()) {
            abort(403);
        }

        $modal_title = 'Delete Supplier Return';

        $return = \App\Models\SupplierReturn::find($id);

        $modal_route = route('admin.supplierreturn.delete', ['id' => $return->id]);

        $modal_body = 'Are you sure you want to delete this Supplier Return?';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function getPurchaseBillId()
    {
        $term = strtolower(\Request::get('term'));
        $purchasebills = \App\Models\PurchaseOrder::where('purchase_type', 'bills')->select('id')->where('id', 'LIKE', '%'.$term.'%')->take(5)->get();
        $return_array = [];

        foreach ($purchasebills as $v) {
            $return_array[] = ['value' =>sprintf('%08d', $v->id), 'id' =>$v->id];
        }

        return Response::json($return_array);
    }

    public function getPurchaseBillInfo(Request $request)
    {
        
        $purchasebillsinfo = \App\Models\PurchaseOrder::whereIn('id',$request->purchasebills_id)->get();
        $customer_name=[];
        $purchasedetailinfo=[];
        $billno=[];
        $purchaseorderdate=[];
        $purchaseowner=[];
        $panno=[];
        $customer_id=[];
        $subtotal=0;
        $taxableamt=0;
        $nontaxable=0;
        $totalamt=0;
        $taxamt=0;
        foreach($purchasebillsinfo as $billinfo){
        $customer_name[] = \App\Models\Client::where('id',$billinfo->supplier_id)->first()->name;
        $customer_id[] = \App\Models\Client::where('id',$billinfo->supplier_id)->first()->id;
        $purchasedetailinfo[] = \App\Models\PurchaseOrderDetail::where('order_no', $billinfo->id)->get();
        $billno[]=$billinfo->bill_no;
        $purchaseorderdate[]=$billinfo->bill_date;
        $purchaseowner[]=$billinfo->user->name;
        $panno[]=$billinfo->pan_no;
        $subtotal+=$billinfo->subtotal;
        $taxableamt+=$billinfo->taxable_amount;
        $nontaxableamt+=$billinfo->non_taxable_amount;
        $totalamt+=$billinfo->total;
        $taxamt+=$billinfo->tax_amount;
    }
        $products = Product::select('id', 'name')->get();
        $data = '';
        foreach ($purchasedetailinfo as $idi) {
            foreach($idi as $i){
            // dd($i->product_id,'check',$purchasedetailinfo);
            $unit_name = \App\Models\ProductsUnit::find($i->units)->name;

            if ($i->is_inventory == 1) {
                $name = \App\Models\Product::find($i->product_id)->name;

                $data .= '<tr>  
                                <td>
                                <input type="text" class="form-control product_id" name="product"  value="'.$name.'" readonly>
                                  <input type="hidden"  name="product_id[]" value="'.$i->product_id.'" required="required" readonly>   
                                </td>
                                <td>
                                    <input type="text" class="form-control invoice_price" placeholder="Unit" value="'.$unit_name.'" required="required" readonly>
                                    <input  type="hidden" name="units[]" value="'.$i->units.'">

                                </td>
                                <td>
                                    <input type="number" class="form-control purchase_quantity" name="purchase_quantity[]" placeholder="Quantity" min="1" value="'.$i->qty_invoiced.'" required="required" readonly>
                                </td>
                               

                                <td>
                                    <input type="number" class="form-control quantity" name="return_quantity[]" placeholder="Return Quantity" min="1" value="'.$i->qty_invoiced.'" required="required" >
                                </td>

                                <td>
                                   <input type="number" class="form-control purchase_price" name="purchase_price[]" placeholder="Purchase Price" min="1" value="'.$i->unit_price.'" required="required" readonly>
                                </td>
                                <td>
                                    <input type="number" class="form-control price" name="return_price[]" placeholder="Credit Price" min="1" value="'.$i->unit_price.'" required="required" >
                                </td>

                                 <td>
                                     <input type="number" class="form-control total" name="return_total[]" placeholder="Total" value="'.$i->total.'" readonly="readonly">
                                </td>

                                <td>
                                    <input type="text" class="form-control reason" name="reason[]" placeholder="Reason" value=""style="float:left; width:80%;">
                                    <a href="javascript::void(1);" style="width: 10%;">
                                        <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                                    </a>
                                </td>
                            </tr>';
            } elseif ($i->is_inventory == 0) {
                $data .= '<tr>
                                <td>
                                  <input type="text" class="form-control product" name="custom_items_name[]" value="'.$i->description.'" placeholder="Product" readonly>
                                </td>
                                <td>
                                    <input type="text" class="form-control invoice_price" placeholder="Unit" value="'.$unit_name.'"  readonly>

                                    <input  type="hidden" name="custom_units[]" value="'.$i->units.'">
                                </td>
                                 
                                <td>
                                    <input type="number" class="form-control purchase_quantity" name="custom_purchase_qty[]" placeholder="Quantity" min="1" value="'.$i->qty_invoiced.'"  readonly>
                                </td>

                                <td>
                                    <input type="number" class="form-control quantity" name="custom_return_qty[]" placeholder="Return Quantity" min="1" value="'.$i->qty_invoiced.'" required="required">
                                </td>

                                <td>
                                    <input type="number" class="form-control purchase_price" name="custom_purchase_price[]" placeholder="Credit Qty" min="1" value="'.$i->unit_price.'" required="required" readonly>
                                </td>

                                <td>
                                    <input type="number" class="form-control price" name="custom_return_price[]" placeholder="Credit Price" min="1" value="'.$i->unit_price.'" required="required" >
                                </td>
                                <td>
                                 <input type="number" class="form-control total" name="custom_return_total[]" placeholder="Total" value="'.$i->total.'" readonly="readonly" >
                                 </td>

                                 
                                <td>
                                    <input type="text" class="form-control reason" name="custom_reason[]" placeholder="Reason" value="" style="float:left; width:80%;">
                                    <a href="javascript::void(1);" style="width: 10%;">
                                        <i class="remove-this btn btn-xs btn-danger icon fa fa-trash deletable" style="float: right; color: #fff;"></i>
                                    </a>
                                </td>
                            </tr>';
            }
        }
    }

        return ['purchasebillsinfo'=>$purchasebillsinfo, 'subtotal'=>$subtotal, 'taxamt'=>$taxamt, 'taxableamt'=>$taxableamt, 'nontaxableamt'=>$nontaxableamt, 'total'=>$totalamt, 'purchasedetailinfo'=>$data, 'customer_id'=>$customer_id, 'customer_name'=>$customer_name, 'bill_no'=>$billno, 'purchaseorderdate'=>$purchaseorderdate, 'purchaseowner'=>$purchaseowner, 'panno'=>$panno];
    }

    private function updateEntries($orderId)
    {
        $purchasereturn = \App\Models\SupplierReturn::find($orderId);

        if ($purchasereturn->entry_id && $purchasereturn->entry_id != '0') { //update the ledgers
            $attributes['entrytype_id'] = \FinanceHelper::get_entry_type_id('debitnote'); //Purchase Return
            $attributes['tag_id'] = '9'; //Debit  Memos
            $attributes['user_id'] = Auth::user()->id;
            $attributes['org_id'] = Auth::user()->org_id;
            $attributes['number'] = $purchasereturn->id;
            $attributes['date'] = \Carbon\Carbon::today();
            $attributes['dr_total'] = $purchasereturn->total_amount;
            $attributes['cr_total'] = $purchasereturn->total_amount;
            $attributes['source'] = 'AUTO_SN';
            $entry = \App\Models\Entry::find($purchasereturn->entry_id);
            $entry->update($attributes);

            // Creddited to Customer or Interest or eq ledger
            $sub_amount = \App\Models\Entryitem::where('entry_id', $purchasereturn->entry_id)->where('dc', 'D')->first();
            $sub_amount->entry_id = $entry->id;
            $sub_amount->user_id = Auth::user()->id;
            $sub_amount->org_id = Auth::user()->org_id;
            $sub_amount->dc = 'D';
            $sub_amount->ledger_id = \App\Models\Client::find($purchasereturn->supplier_id)->ledger_id; //Client ledger
            $sub_amount->amount = $purchasereturn->total_amount;
            $sub_amount->narration = 'Supplier Return'; //$request->user_id
            //dd($sub_amount);
            $sub_amount->update();

            // Debitte to Bank or cash account that we are already in
            $cash_amount = \App\Models\Entryitem::where('entry_id', $purchasereturn->entry_id)->where('dc', 'C')->first();
            $cash_amount->entry_id = $entry->id;
            $cash_amount->user_id = Auth::user()->id;
            $cash_amount->org_id = Auth::user()->org_id;
            $cash_amount->dc = 'C';
            $cash_amount->ledger_id = \FinanceHelper::get_ledger_id('PURCHASE_LEDGER_ID'); // Purchase ledger if selected or ledgers from .env
            // dd($cash_amount);
            $cash_amount->amount = $purchasereturn->total_amount;
            $cash_amount->narration = 'Supplier Return';
            $cash_amount->update();
        } else {                               //create the new entry items
            $attributes['entrytype_id'] = \FinanceHelper::get_entry_type_id('debitnote'); //Credit Notes
            $attributes['tag_id'] = '9'; //Credit Memos
            $attributes['user_id'] = Auth::user()->id;
            $attributes['org_id'] = Auth::user()->org_id;
            $attributes['number'] = $purchasereturn->id;
            $attributes['date'] = \Carbon\Carbon::today();
            $attributes['dr_total'] = $purchasereturn->total_amount;
            $attributes['cr_total'] = $purchasereturn->total_amount;
            $attributes['source'] = 'AUTO_SN';
            $entry = \App\Models\Entry::create($attributes);

            // Creddited to Customer or Interest or eq ledger
            $sub_amount = new \App\Models\Entryitem();
            $sub_amount->entry_id = $entry->id;
            $sub_amount->user_id = Auth::user()->id;
            $sub_amount->org_id = Auth::user()->org_id;
            $sub_amount->dc = 'D';
            $sub_amount->ledger_id = \App\Models\Client::find($purchasereturn->supplier_id)->ledger_id; //Client ledger
            $sub_amount->amount = $purchasereturn->total_amount;
            $sub_amount->narration = 'Supplier Return'; //$request->user_id
            //dd($sub_amount);
            $sub_amount->save();

            // Debitte to Bank or cash account that we are already in

            $cash_amount = new \App\Models\Entryitem();
            $cash_amount->entry_id = $entry->id;
            $cash_amount->user_id = Auth::user()->id;
            $cash_amount->org_id = Auth::user()->org_id;
            $cash_amount->dc = 'C';
            $cash_amount->ledger_id = \FinanceHelper::get_ledger_id('PURCHASE_LEDGER_ID'); // Sales ledger if selected or ledgers from .env
            // dd($cash_amount);
            $cash_amount->amount = $purchasereturn->total_amount;
            $cash_amount->narration = 'Supplier Return';
            $cash_amount->save();

            //now update entry_id in income row
            $purchasereturn->update(['entry_id'=>$entry->id]);
        }

        return 0;
    }
}
