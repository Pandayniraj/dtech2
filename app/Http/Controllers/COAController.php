<?php namespace App\Http\Controllers;

use App\Models\Role as Permission;
use App\Models\Audit as Audit;
use Flash;
use DB;
use Auth;
use App\Helpers\TaskHelper;
use App\User;
use App\Models\Department;
use App\Models\Entryitem;
use App\Models\COAgroups;
use App\Models\COALedgers;
use App\Models\Designation;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * THIS CONTROLLER IS USED AS PRODUCT CONTROLLER
 */

class COAController extends Controller
{
    /**
     * @var Permission
     */
    private $permission;

    /**
     * @param Permission $permission
     */
    public function __construct(Permission $permission)
    {
        parent::__construct();
        $this->permission = $permission;
    }

    // Stock Category
    public function index()
    {
        $page_title = 'Chart Of Account';

        $page_description = 'All Chart of accounts';

        $groups= COAgroups::orderBy('code', 'asc')->get();
        // if(\Request::get('option')=='excel'){

        // }

        return view('admin.coa.index', compact('page_title','page_description','groups'));
    }
    public function CreateGroups(){

      $page_title = 'Groups Add';
      $page_description = 'create groups';

      $groups= COAgroups::orderBy('code','asc')->get();


    return view('admin.coa.creategroups', compact('page_title','page_description','groups'));

    }

    public function PostGroups(Request $request){

      $this->validate($request, array(
          'parent_id' => 'required',
          'name' => 'required'
        ));

      $check = COAgroups::where('code',$request->code)->where('org_id',\Auth::user()->org_id)->exists();
      if($check){
          return \Redirect::back()->withErrors(['error'=>'Code Already Taken']);
      }
      //dd($request->all());
      $detail = new COAgroups();
      $detail->parent_id= $request->parent_id;
      $detail->org_id = \Auth::user()->org_id;
      $detail->user_id = \Auth::user()->id;
      $detail->code = $request->code;
      $detail->name= $request->name;
      $detail->affects_gross= $request->affects_gross;
      $detail->save();

     Flash::success("Groups Created Successfully");

      return redirect('/admin/chartofaccounts');

    }

     public function CreateLedgers(){

      $page_title = 'Ledgers Add';
      $page_description = 'create Ledgers';

      $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();
      if(\Request::ajax()){
        $expenses_type = $_GET['expenses_type']; //for detrmining which select option
        $selectedgroup_value =$_GET['selected_value'];
        return view('admin.coa.modals.createledgers',compact('page_title','page_description','groups','expenses_type','selectedgroup_value'));
      }
     return view('admin.coa.createledgers', compact('page_title','page_description','groups'));

    }


     public function PostLedgers(Request $request){
      if(\Request::ajax()){
            $validator = \Validator::make($request->all(), [
              'code' => 'required | unique:coa_ledgers',
              'group_id' => 'required',
              'name' => 'required',
              'op_balance_dc' => 'required',
              'op_balance' => 'required'
            ]);
            if ($validator->fails()) {
                return ['error'=>$validator->errors()];
            }
        }
       $this->validate($request, array(
          'group_id' => 'required',
          'name' => 'required',
          'op_balance_dc' => 'required',
          'op_balance' => 'required'

         ));
      $check = COALedgers::where('code',$request->code)->where('org_id',\Auth::user()->org_id)->exists();
      if($check){
          return \Redirect::back()->withErrors(['error'=>'Code Already Taken']);
      }
      $detail = new COALedgers();
      $detail->group_id= $request->group_id;

      $detail->org_id = \Auth::user()->org_id;
      $detail->user_id = \Auth::user()->id;

      $detail->code= $request->code;
      $detail->name= $request->name;
      $detail->op_balance_dc= $request->op_balance_dc;
      $detail->op_balance= $request->op_balance;
      $detail->notes= $request->notes;

      if($request->type == 1){
         $detail->type= $request->type;
      }else{
        $detail->type=0;
      }
      if($request->reconciliation == 1){
         $detail->type= $request->reconciliation;
      }else{
        $detail->reconciliation=0;
      }
      if($request->affect_stock == 1){
         $detail->affect_stock  = $request->affect_stock;
      }

      $detail->save();


      if(\Request::ajax()){
        if(isset($_GET['selectedgroup'])){
          $selectedgroup = $_GET['selectedgroup'];
          $data = view('admin.coa.modals.ajaxledgergroup-select', compact('selectedgroup'))->render();
        }else{
           $lastcreated = $detail;
           $data = view('admin.coa.modals.ajaxledgergroup-select', compact('lastcreated'))->render();
        }
        return ['data'=>$data,'status'=>'success','lastcreated'=>$detail];
      }
      Flash::success("Ledgers Created Successfully");

      return redirect('/admin/chartofaccounts');

    }

   public function DetailGroups($id,Request $request)
    {
      $page_title = 'Groups Detail';
      $page_description = 'Detail Groups';

      // $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();

      if(!empty($request->input('group_id')))
      {
       $groups_data = COAgroups::find($request->group_id);
       $ledgers_ids = $this->get_ledger_ids($request->group_id);
      }else{
       $groups_data = COAgroups::find($id);
       $ledgers_ids = $this->get_ledger_ids($id);
      }

      $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();

      if(!empty($request->input('startdate')) && !empty($request->input('enddate'))){
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        $entry_items = Entryitem::whereIn('ledger_id',$ledgers_ids)->leftjoin('entries','entryitems.entry_id','=','entries.id')->where('entries.date','>=',$startdate)->where('entries.date','<=',$enddate)->get();
      }
      else{
        $entry_items= Entryitem::whereIn('ledger_id',$ledgers_ids)->get();
      }
     return view('admin.coa.detailgroups', compact('page_title','page_description','groups','groups_data','entry_items','id'));
    }

    public function get_ledger_ids($id)
    {
         $groups_data = COAgroups::find($id);
         if(count($groups_data->children) > 0)
         {
           $group_ids = COAgroups::where('parent_id',$id)->pluck(id);
           $ledgers_ids = COALedgers::whereIn('group_id',$group_ids)->pluck(id);
         }else
         {
           $ledgers_ids = COALedgers::where('group_id',$id)->pluck(id);
         }
         return $ledgers_ids;
    }

     public function EditGroups($id){

       $page_title = 'Groups Edit';
       $page_description = 'edit groups';
       $groups= COAgroups::orderBy('code','asc')->get();

       $group_data= COAgroups::find($id);


       return view('admin.coa.editgroups', compact('page_title','page_description','groups','group_data'));

    }

     public function UpdateGroups(Request $request,$id){

        $coagroups = COAgroups::find($id);
        $original_value= COAGroups::where('id',$id)->first()->code;

        if($request->code != $original_value){

         $this->validate($request, array(
          'code' => 'required |  unique:coa_groups'
         ));

         }

         if($id<=4){
          Flash::error('Permission Denied To Update this Group');
          return redirect('/admin/chartofaccounts');
         }

        $this->validate($request, array(
          'name' => 'required',
          'parent_id' => 'required'
         ));

        $attributes = $request->all();
        $attributes['org_id'] = \Auth::user()->org_id;
        $attributes['user_id'] = \Auth::user()->id;

        $coagroups->update($attributes);
        Flash::success('Group Updated Successfully');

      return redirect('/admin/chartofaccounts');

    }



     public function EditLedgers($id){

      $page_title = 'Ledgers Edit';
      $page_description = 'edit Ledgers';

      $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();

      $ledgers_data= COALedgers::find($id);


     return view('admin.coa.editledgers', compact('page_title','page_description','groups','ledgers_data'));


    }

    public function UpdateLedgers(Request $request,$id){

      $coaledgers = COALedgers::find($id);

      $original_value= COALedgers::where('id',$id)->first()->code;

      if($request->code != $original_value){




         $this->validate($request, array(
          'code' => 'required |  unique:coa_ledgers'
         ));



       }

       $this->validate($request, array(
          'name' => 'required',
          'group_id' => 'required',
          'op_balance_dc' => 'required',
          'op_balance' => 'required'

         ));

      $attributes = $request->all();

      $attributes['org_id'] = \Auth::user()->org_id;
      $attributes['user_id'] = \Auth::user()->id;

      $attributes['type']= $request->type;
      $attributes['reconciliation']= $request->reconciliation;
      $attributes['affect_stock']= $request->affect_stock;

      $coaledgers->update($attributes);
      Flash::success('Ledgers Updated Successfully');

    return redirect('/admin/chartofaccounts');

    }


     public function DetailLedgers($id,Request $request){
      $page_title = 'Ledgers Detail';
      $page_description = 'Detail Ledgers';
      $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();
      $ledgers_data= COALedgers::find($id);

      if(\Request::has('startdate') && \Request::has('enddate')){
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        $entry_items = Entryitem::where('ledger_id',$id)->leftjoin('entries','entryitems.entry_id','=','entries.id')->where('entries.date','>=',$startdate)->where('entries.date','<=',$enddate)->get();
      }
      else{
        $entry_items= Entryitem::where('ledger_id',$id)->get();
      }
     return view('admin.coa.detailledgers', compact('page_title','page_description','groups','ledgers_data','entry_items','id'));
    }


  public function DownloadPdf(Request $request,$id){

     $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();
     $ledgers_data= COALedgers::find($id);

      if(\Request::get('startdate') && \Request::get('enddate')){
        $startdate = $request->startdate;
        $enddate = $request->enddate;

        $entry_items = Entryitem::where('ledger_id',$id)->leftjoin('entries','entryitems.entry_id','=','entries.id')->where('entries.date','>=',$startdate)->where('entries.date','<=',$enddate)->get();
      }
      else{
        $entry_items= Entryitem::where('ledger_id',$id)->get();
      }



         $imagepath=\Auth::user()->organization->logo;

        $pdf = \PDF::loadView('admin.coa.pdf', compact('entries','entriesitem','groups','ledgers_data','entry_items','id','imagepath','startdate','enddate'));

        $file = $id.'_'.$entries->number.'.pdf';

        if (\File::exists('reports/'.$file))
        {
            \File::Delete('reports/'.$file);
        }
        return $pdf->download($file);

    }

     public function PrintLedgers(Request $request,$id){



      $page_title = 'Entry Show';
      $page_description = 'show entries';
      $imagepath=\Auth::user()->organization->logo;


      $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();
      $ledgers_data= COALedgers::find($id);
      if(\Request::get('startdate') && \Request::get('enddate')){
      $startdate = $request->startdate;
      $enddate = $request->enddate;
      $entry_items = Entryitem::where('ledger_id',$id)->leftjoin('entries','entryitems.entry_id','=','entries.id')->where('entries.date','>=',$startdate)->where('entries.date','<=',$enddate)->get();
      }
      else{
      $entry_items= Entryitem::where('ledger_id',$id)->get();
      }

      return view('admin.coa.print', compact('entries','entriesitem','groups','ledgers_data','entry_items','id','imagepath','startdate','enddate'));

    }

    public function downloadExcel($id){
      $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();
      $ledgers_data= COALedgers::find($id);
      $entry_items= Entryitem::where('ledger_id',$id)->get();
      $mytime = \Carbon\Carbon::now();
      $startOfYear = $mytime->copy()->startOfYear();
      $endOfYear   = $mytime->copy()->endOfYear();
      $type = ($ledgers_data->op_balance_dc == 'D')?'Dr':'Cr';
      $closing_balance = \TaskHelper::getLedgerBalance($id)['ledger_balance'];
      $heading = [
        [
          'Bank or cash account',($ledger_data->type == 1) ? 'Yes' : 'No',
        ],
        [
          'Notes',$ledgers_data->notes,
        ],
        [
          'Opening balance as on',date('d-M-Y', strtotime($startOfYear)),$type.' '.$ledgers_data->op_balance
        ],
        [
          'Closing balance as on ',date('d-M-Y', strtotime($endOfYear)),$closing_balance
        ]

      ];
      $total=0;
      foreach($entry_items as $ei){
        $entry_balance = \TaskHelper::calculate_withdc($entry_balance['amount'], $entry_balance['dc'],
          $ei['amount'], $ei['dc']);
        $getledger= \TaskHelper::getLedger($ei->entry_id);
        $type = $ei->dc=='D'?'Dr':'Cr';
        $entry[] = [
          'entry_date'=>$ei->entry->date,
          'entry_number'=>$ei->entry->number,
          'ledger'=>$getledger,
          'entry_type'=>$ei->entry->entrytype->name,
          'tagname'=>$ei->entry->tagname->title,
          'balance'=> $type.' '.$ei->amount,
        ];
        $total = $total + $ei->amount;
     }
     $entry[] = ['','','','','Total',$total];
    return \Excel::create('leadger_report('.$id.')', function($excel) use ($entry,$heading) {
          $excel->sheet('mySheet', function($sheet) use ($entry,$heading)
            {
              $sheet->fromArray($entry);
              $sheet->prependRow($heading[3]);
              $sheet->prependRow($heading[2]);
              $sheet->prependRow($heading[1]);
              $sheet->prependRow($heading[0]);
              $sheet->cell('A5:F5', function($cell){
                  $cell->setFontWeight('bold');
                  $cell->setBackground('#ffff00');
              });
              $sheet->cell('A1', function($cell){
                    $cell->setFontWeight('bold');
              });
              $sheet->cell('A2', function($cell){
                    $cell->setFontWeight('bold');
              });
              $sheet->cell('A3', function($cell){
                    $cell->setFontWeight('bold');
              });
              $sheet->cell('A4', function($cell){
                    $cell->setFontWeight('bold');
              });
              $last_row = count($entry) + count($heading) + 1;
              $sheet->cell('E'.$last_row.':F'.$last_row, function($cell){
                    $cell->setFontWeight('bold');
                    $cell->setBackground('#ffff00');
                });
            });
        })->download(xls);
    }


    public function GetNextCode(Request $request){

     $id=$request->id;
     $group_data= COAgroups::find($id);
     $group_code=$group_data->code;
     $g= COAgroups::where('parent_id',$id)->where('org_id',\Auth::user()->org_id)->where('code','!=','null')->get();
     if(count($g) > 0){
       $last= $g->last();
       $last = $last->code;
       $l_array = explode('-', $last);
       $new_index = end($l_array);
       $new_index += 1;
       $new_index = sprintf("%02d", $new_index);
       $code=$group_code."-".$new_index;
       return $code;
     }
     else{
      $code= $group_code."-01";
      return $code;
     }
    }
    public function getBalanceSheetLedgersAjax(Request $request)
    {
        $request->validate([
            'group_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $ledgers = \App\Models\COALedgers::orderBy('code', 'asc')
            ->where([['org_id', auth()->user()->org_id], ['group_id', $request->group_id]])->get();
        if (count($ledgers) > 0) {
            $sub_mark = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp";
            $sub_mark = $sub_mark . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

            $ledger_row = '';
            foreach ($ledgers as $ledger) {
                $opening_balance = TaskHelper::getLedgersOpeningBalance($ledger, $start_date);
                $dr_cr = TaskHelper::getLedgerDrCr($ledger, $start_date, $end_date);
                $closing_balance = \App\Helpers\TaskHelper::getLedgerClosing($opening_balance, $dr_cr['dr_total'], $dr_cr['cr_total']);
                if ($closing_balance['dc'] == 'D') {
                    $ledger_row .= '<tr class="bg-danger ledger-class">
                                        <td class="bg-warning"><a href="/admin/accounts/reports/ledger_statement?ledger_id=' . $ledger->id . '">' . $sub_mark . '[' . $ledger->code . ']' . $ledger->name . '</a></td>
                                        <td class="bg-warning f-16">Dr <span>' . number_format($closing_balance['amount'], 2) . '</span></td>
                                    </tr>';
                } else {
                    $ledger_row .= '<tr class="bg-warning ledger-class">
                                        <td class="bg-danger"><a href="/admin/accounts/reports/ledger_statement?ledger_id=' . $ledger->id . '">' . $sub_mark . '[' . $ledger->code . ']' . $ledger->name . '</a></td>
                                        <td class="bg-danger f-16">Cr <span>' . number_format($closing_balance['amount'], 2) . '</span></td>
                                    </tr>';
                }
            }
        }
         return response()->json($ledger_row);

    }

  public function getNextCodeLedgers(Request $request) {
    $id=$request->id;
    $group_data= COAgroups::find($id);
    $group_code=$group_data->code;
    $q= COALedgers::where('group_id',$id)->where('org_id',\Auth::user()->org_id)->where('code','!=','null')->get();
    if(count($q) > 0){
      $codes =[];
      foreach($q as $c){
        $code = $c->code;
        $codearr = explode('-', $code);
        array_push($codes,(int)$codearr[count($codearr)-1]);
      }
      $new_index = max($codes) + 1;
      $new_index = sprintf("%04d", $new_index);
      return $group_code."-".$new_index;
    }else{

      return $group_code."-0001";
    }

  }



  public function getModalDeleteGroups($id){
      $error = null;

        $groups = COAgroups::find($id);

         $modal_title = 'Delete Group';


         $modal_route = route('admin.chartofaccounts.groups.delete', array('orderId' => $groups->id));

        $modal_body = 'Are you sure you want to delete this Group?';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));


  }

  public function destroyGroups($id){

        $groups = COAgroups::find($id);

        if($id ==''){
          Flash::error('Group Not Specified.');
          return redirect('/admin/chartofaccounts');
        }

        if($id <='4'){
          Flash::error('Permission Denied You cannot Delete this Group');
          return redirect('/admin/chartofaccounts');
        }

         $no_of_child=COAgroups::where('parent_id',$id)->get();
          if(count($no_of_child)>0){

          Flash::error('Child Group Exists So cannot Delete Group');
          return redirect('/admin/chartofaccounts');
        }

         $no_of_ledgers=COALedgers::where('group_id',$id)->get();

          if(count($no_of_ledgers)>0){

          Flash::error('Ledgers Of Group Exists So cannot Delete Group');
          return redirect('/admin/chartofaccounts');
        }




        COAgroups::find($id)->delete();

        Flash::success('Group successfully deleted.');


        return redirect('/admin/chartofaccounts');

  }

  public function getModalDeleteLedgers($id){
      $error = null;

        $ledgers = COALedgers::find($id);

         $modal_title = 'Delete Ledger';

         $ledgers = COALedgers::find($id);

         $modal_route = route('admin.chartofaccounts.ledgers.delete', $ledgers->id);

         $modal_body = 'Are you sure you want to delete this Ledgers?';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));

  }

  public function destroyLedgers($id){

        $ledgers = COALedgers::find($id);


        if($id ==''){

          Flash::error('Ledger Not Specified.');
          return redirect('/admin/chartofaccounts');

        }

        $no_of_ledgers=\App\Models\Entryitem::where('ledger_id',$id)->get();

        if(count($no_of_ledgers)>0){

          Flash::error('Entries Of Ledger Exists So cannot Delete Ledger');
          return redirect('/admin/chartofaccounts');

        }

        COALedgers::find($id)->delete();

        Flash::success('Ledger successfully deleted.');

        return redirect('/admin/chartofaccounts');

  }

  public function excelLedger(){
    $page_title = 'Admin | Export | Import';
    $page_description = "Export Import Ledger";
    return view('admin.excel.importExportLedger',compact('page_description','page_title'));
  }

  public function exportLedger($type){
  $data = COALedgers::where('org_id',\Auth::user()->org_id)->get()->toArray();


   return \Excel::download(new \App\Exports\ExcelExport($data), "ledgers.{$type}");


  }
  public function importLedger(Request $request){
    if(\Request::hasFile('import_file')){
      $path = \Request::file('import_file')->getRealPath();

      $data  = \Excel::toCollection(new \App\Exports\ExcelImport(), \Request::file('import_file'));

      if(!empty($data) && $data->count()){
       $data = $data->first()->toArray();
        foreach ($data as $key => $value) {
          $value = (object) $value;
          $insert [] = [
            'group_id' => $value->group_id,
            'name' => $value->name,
            'op_balance_dc' => $value->op_balance_dc,
            'op_balance' => $value->op_balance,
            'org_id'=>\Auth::user()->org_id,
            'user_id'=>\Auth::user()->id,
            'type'=>$value->type ? '1' : '0',
            'reconciliation'=>$value->type ? '1' : '0',
            'notes'=>$value->notes
          ];

        }
        if(!empty($insert)){
          $ledger = COALedgers::insert($insert);
          $lastcreated = COALedgers::orderBy('id', 'desc')->take(count($insert))->get();
          $lastcreated = $lastcreated->reverse();
          foreach ($lastcreated as $key => $ledger) {
            $request->request->add(['id'=>$ledger->group_id]);
            $this->getNextCodeLedgers($request);
            $code = $this->getNextCodeLedgers($request);
            $ledger->update(['code'=>$code]);
          }
        }
      }
      Flash::success("Ledger successfully added");
      return redirect()->back();
    }
    Flash::success('Sorry no file is selected to import leads.');
    return redirect()->back();

  }
  public function excelLedgergroups(){
    $page_title = 'Admin | Export | Import';
    $page_description = "Export Import Ledger";
    return view('admin.excel.importExportLedgerGroup',compact('page_description','page_title'));
  }

  public function exportLedgergroups($type){
    $data = COAgroups::where('org_id',\Auth::user()->org_id)->get()->toArray();
    return \Excel::download(new \App\Exports\ExcelExport($data), "ledgers_groups.{$type}");
  }
  public function importLedgergroups(Request $request){
    if(\Request::hasFile('import_file')){
      $path = \Request::file('import_file')->getRealPath();

      $data  = \Excel::toCollection(new \App\Exports\ExcelImport(), \Request::file('import_file'));

      if(!empty($data) && $data->count()){
        $data = $data->first()->toArray();
        foreach ($data as $key => $value) {
          $value = (object) $value;
          $insert [] = [
            'parent_id'=> $value->parent_id,
            'name' => $value->name,
            'user_id'=>\Auth::user()->id,
            'org_id'=>\Auth::user()->org_id
          ];
        }

        if(!empty($insert)){
          $ledger = COAgroups::insert($insert);
          $lastcreated = COAgroups::orderBy('id', 'desc')->take(count($insert))->get();
          $lastcreated = $lastcreated->reverse();
          foreach ($lastcreated as $key => $ledgergrp) {
            $request->request->add(['id'=>$ledgergrp->parent_id]);
            $this->getNextCodeLedgers($request);
            $code = $this->GetNextCode($request);
            $ledgergrp->update(['code'=>$code]);
          }
        }
      }
      Flash::success("Ledgers Groups successfully added");
      return redirect()->back();
    }
      Flash::success('Sorry no file is selected to import leads.');
      return redirect()->back();
  }

  public function filterByGroups(){

          $parentgroups = \App\Models\COAgroups::where('parent_id',null)
                            ->where('org_id',\Auth::user()->org_id)->get();

          $page_title = "Admin | COA | Filter | Groups";

          $page_description = "List of Groups and Ledgers By Parent Group";

          //dd($parentgroups);

         return view('admin.coa.filterbygroups',compact('parentgroups','page_title','page_description'));

  }

    public function filterByGroupPost(Request $request){


        $parent_id = $request->parent_id;
        $page_title = "Ledgers By Main Account Heads";

        $page_description = "List of Groups and Ledgers By Parent Group";
        $parentgroups = \App\Models\COAgroups::where('parent_id',null)->where('org_id',\Auth::user()->org_id)->get();
        $maingroup='';
        $maingroupledgers=[];
        if (!$parent_id)
            return view('admin.coa.filterbygroupdetail',compact('page_title','page_description','parentgroups','parent_id','maingroup','maingroupledgers'));


        $maingroup = \App\Models\COAGroups::find($parent_id);

        //dd($maingroup);
        $maingroupledgers = \App\Models\COALedgers::orderBy('code', 'asc')->where('group_id',$maingroup->id)->where('org_id',\Auth::user()->org_id)->get();

        //dd($maingroupledgers);




        return view('admin.coa.filterbygroupdetail',compact('page_title','page_description','parentgroups','parent_id','maingroup','maingroupledgers'));

    }


  public function DownloadGroupPdf(Request $request,$id)
  {
     $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();
     // $ledgers_data= COALedgers::find($id);

       if(!empty($request->input('group_id')))
      {
       $groups_data = COAgroups::find($request->group_id);
       $ledgers_ids = $this->get_ledger_ids($request->group_id);
      }else{
       $groups_data = COAgroups::find($id);
       $ledgers_ids = $this->get_ledger_ids($id);
      }

      $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();

      if(!empty($request->input('startdate')) && !empty($request->input('enddate'))){
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        $entry_items = Entryitem::whereIn('ledger_id',$ledgers_ids)->leftjoin('entries','entryitems.entry_id','=','entries.id')->where('entries.date','>=',$startdate)->where('entries.date','<=',$enddate)->get();
      }
      else{
        $entry_items= Entryitem::whereIn('ledger_id',$ledgers_ids)->get();
      }


         $imagepath=\Auth::user()->organization->logo;

        $pdf = \PDF::loadView('admin.coa.groupDetailPdf', compact('entries','entriesitem','groups','groups_data','entry_items','id','imagepath'));

        $file = $id.'_'.$entries->number.'.pdf';

        if (\File::exists('reports/'.$file))
        {
            \File::Delete('reports/'.$file);
        }
        return $pdf->download($file);
  }

    public function downloadGroupExcel(Request $request,$id)
    {
      $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();

     if(!empty($request->input('group_id')))
      {
       $groups_data = COAgroups::find($request->group_id);
       $ledgers_ids = $this->get_ledger_ids($request->group_id);
      }else{
       $groups_data = COAgroups::find($id);
       $ledgers_ids = $this->get_ledger_ids($id);
      }

      // $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();

      if(!empty($request->input('startdate')) && !empty($request->input('enddate'))){
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        $entry_items = Entryitem::whereIn('ledger_id',$ledgers_ids)->leftjoin('entries','entryitems.entry_id','=','entries.id')->where('entries.date','>=',$startdate)->where('entries.date','<=',$enddate)->get();
      }
      else{
        $entry_items= Entryitem::whereIn('ledger_id',$ledgers_ids)->get();
      }


      $mytime = \Carbon\Carbon::now();
      $startOfYear = $mytime->copy()->startOfYear();
      $endOfYear   = $mytime->copy()->endOfYear();
      // $type = ($groups_data->op_balance_dc == 'D')?'Dr':'Cr';
      // $closing_balance = \TaskHelper::getLedgerBalance($id)['ledger_balance'];
      // $heading = [
      //   [
      //     'Bank or cash account',($ledger_data->type == 1) ? 'Yes' : 'No',
      //   ],
      //   [
      //     'Notes',$groups_data->notes,
      //   ],
      //   [
      //     'Opening balance as on',date('d-M-Y', strtotime($startOfYear)),$type.' '.$groups_data->op_balance
      //   ],
      //   [
      //     'Closing balance as on ',date('d-M-Y', strtotime($endOfYear)),$closing_balance
      //   ]

      // ];

      $total=0;
      foreach($entry_items as $ei){
        // $entry_balance = \TaskHelper::calculate_withdc($entry_balance['amount'], $entry_balance['dc'],
          // $ei['amount'], $ei['dc']);
        $getledger= \TaskHelper::getLedger($ei->entry_id);
        $type = $ei->dc=='D'?'Dr':'Cr';
        $entry[] = [
          'entry_date'=>$ei->entry->date,
          'entry_number'=>$ei->entry->number,
          'ledger'=>$ei->ledgerdetail->name,
          'Description'=>$getledger,
          'entry_type'=>$ei->entry->entrytype->name,
          'tagname'=>$ei->entry->tagname->title,
          'Dr'=>$ei->dc=='D'? $ei->amount:'0',
          'Cr'=>$ei->dc!='D'? $ei->amount:'0',
          'balance'=> $type.' '.$ei->amount,
        ];
        $total = $total + $ei->amount;
     }
     $entry[] = ['','','','','','','Total',$total];
     return \Excel::download(new \App\Exports\ExcelExport($entry), 'group_'.$groups_data->name.'.csv');
    }

    public function PrintGroups(Request $request,$id)
    {
       $page_title = 'Entry Show';
       $page_description = 'show entries';
       $imagepath=\Auth::user()->organization->logo;


        $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();

       if(!empty($request->input('group_id')))
      {
       $groups_data = COAgroups::find($request->group_id);
       $ledgers_ids = $this->get_ledger_ids($request->group_id);
      }else{
       $groups_data = COAgroups::find($id);
       $ledgers_ids = $this->get_ledger_ids($id);
      }

      $groups= COAgroups::orderBy('code', 'asc')->where('org_id',\Auth::user()->org_id)->get();

      if(!empty($request->input('startdate')) && !empty($request->input('enddate'))){
        $startdate = $request->startdate;
        $enddate = $request->enddate;
        $entry_items = Entryitem::whereIn('ledger_id',$ledgers_ids)->leftjoin('entries','entryitems.entry_id','=','entries.id')->where('entries.date','>=',$startdate)->where('entries.date','<=',$enddate)->get();
      }
      else{
        $entry_items= Entryitem::whereIn('ledger_id',$ledgers_ids)->get();
      }
         return view('admin.coa.printgroups', compact('entries','entriesitem','$groups','groups_data','entry_items','id','imagepath'));
    }

    public function get_ledger()

  {

        $term = strtolower(\Request::get('term'));

        $sub_groups = \App\Models\COAgroups::where('parent_id',20)->pluck('id')->toArray();

        //dd($sub_groups);

        //dd($term);

        $ledgers = \App\Models\COALedgers::select('id', 'name')
                  ->whereIn('group_id',$sub_groups)
                  ->where('name', 'LIKE', '%'.$term.'%')
                  ->groupBy('name')
                  ->take(10)->get();



      //dd($ledgers);

        $return_array = array();



        foreach ($ledgers as $v) {

        if (strpos(strtolower($v->name), $term) !== FALSE) {

                $return_array[] = array('value' => $v->name , 'id' =>$v->id);

            }

        }

        return \Response::json($return_array);

    }
    public function deleteSelected(Request $request){
        \App\Models\COALedgers::destroy($request->ledger_ids);

        Flash::success('Ledgers Deleted Successfully');
        return redirect()->back();
    }
}
