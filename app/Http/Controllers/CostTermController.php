<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CostTerm;
use App\Models\COALedgers;
use Flash;
class CostTermController extends Controller
{
    public function index(){
        $page_title="Cost Terms";
        $page_description="additional cost type";
        $costterms=CostTerm::all();
        return view('admin.costterms.index', compact('page_title', 'page_description', 'costterms'));
    }
    public function create(){
        $page_title= "Cost Terms";
        $page_description = "create new cost terms";
        $ledger_all=COALedgers::pluck('name','id');
        return view('admin.costterms.create', compact('ledger_all', 'page_title', 'page_description'));
    }
    public function store(Request $request){
        $attributes= $request->all();
        $costterm= CostTerm::create($attributes);
        Flash::success('New CostTerm added Successfully.');
        return redirect('admin/costterm');
    }
    public function edit($id){
        $page_title="Cost Terms";
        $page_description="edit cost term details";
        $costterm= CostTerm::find($id);
        $ledger_all=COALedgers::select('name','id')->get();
        return view('admin.costterms.edit', compact('costterm', 'ledger_all'));
    }
    public function update($id, Request $request){
        // dd($request->all());
        $costterm=CostTerm::findorfail($id)->update(['name'=>$request->name, 'dr_ledger_id'=>$request->dr_ledger_id, 'cr_ledger_id'=>$request->cr_ledger_id ]);
        Flash::success('CostTerm updated Successfully.');
        return redirect('admin/costterm');
    }
    public function destroy($id)
    {
        $reason =CostTerm::find($id);

    
        $reason->delete();


        Flash::success('CostTerm deleted Successfully.');


        return redirect('/admin/costterm');
    }

    public function deleteModal($id)
    {
        $error = null;

        $reason = CostTerm::find($id);

       
        $modal_title = 'Delete CostTerm';
     
        $modal_route = route('admin.costterm.delete', ['id' => $reason->id]);

        $modal_body = 'Are you sure you want to delete this costterm?';

        return view('modal_confirmation', compact('error', 'modal_route', 'modal_title', 'modal_body'));
    }

    public function getledgerid($id)
    {
        $ledgerid = CostTerm::select('dr_ledger_id', 'cr_ledger_id')
                ->where('name', $id)
                ->first();
                // dd($ledgerid, $id);
        return ['data' => json_encode($ledgerid)];
    }
}
