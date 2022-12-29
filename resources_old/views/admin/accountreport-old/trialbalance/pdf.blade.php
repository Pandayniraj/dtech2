<!DOCTYPE html>
<html lang="en"><head>
    <meta charset="utf-8">
    <title>{{ env('APP_COMPANY')}} | {{ ucfirst(Request::segment(4)) }}</title>

 <style type="text/css">
      
@font-face {
  font-family: SourceSansPro;
  src: url(SourceSansPro-Regular.ttf);
}

.clearfix:after {
  content: "";
  display: table;
  clear: both;
}
a {
  color: #0087C3;
  text-decoration: none;
}
body {
  position: relative;
  width: 18cm;  
  height: 24.7cm; 
  margin: 0 auto; 
  color: #555555;
  background: #FFFFFF; 
  font-family: Arial, sans-serif; 
  font-size: 12px; 
}
header {
  padding: 10px 0;
  margin-bottom: 20px;
  border-bottom: 1px solid #AAAAAA;
}



table {
  width: 100%;
  border-collapse: collapse;
  border-spacing: 0;
  margin-bottom: 5px;
}

table th,
table td {
  padding: 3px;
  background:;
  text-align: left;
  /*border-bottom: 1px solid #FFFFFF;*/
}

table th {
  white-space: nowrap;        
  font-weight: normal;
}

table td {
  text-align: left;
}

table td h3{
  color: #57B223;
  font-size: 1.2em;
  font-weight: normal;
  margin: 0 0 0.2em 0;
}

table .no {
  color: #FFFFFF;
  font-size: 1em;
  background: #57B223;
}

table .desc {
  text-align: left;
}

table .unit {
  background: #DDDDDD;
}

table .qty {
}

table .total {
  background: #57B223;
  color: #FFFFFF;
}

table td.unit,
table td.qty,
table td.total {
  font-size: 1.2em;
}
/*
table tbody tr:last-child td {
  border: none;
}*/

table tfoot td {
  padding: 5px 10px;
  /*background: #FFFFFF;*/
  border-bottom: none;
  /*font-size: 1em;*/
  /*white-space: nowrap; */
  border-top: 1px solid #AAAAAA; 
}

table tfoot tr:first-child td {
  border-top: none; 
}

table tfoot tr:last-child td {
  color: #57B223;
  font-size: 1em;
  border-top: 1px solid #57B223; 
  font-weight: bold;

}

/*table tfoot tr td:first-child {
  border: none;
}*/

#thanks{
  font-size: 2em;
  margin-bottom: 50px;
}

#notices{
  padding-left: 6px;
  border-left: 6px solid #0087C3;  
}

#notices .notice {
  font-size: 1.2em;
}

footer {
  /*color: #777777;*/
  width: 100%;
  height: 30px;
  position: absolute;
  bottom: 0;
  border-top: 1px solid #AAAAAA;
  padding: 8px 0;
  text-align: center;
}

    </style>

  <?php

function CategoryTree($parent_id=null,$sub_mark=''){

  $groups= \App\Models\COAgroups::orderBy('code', 'asc')
          ->where('parent_id',$parent_id)->where('org_id',auth()->user()->org_id)->get();

    if(count($groups)>0){
      foreach($groups as $group){ 

        //dd($group->id);

        $cashbygroup = \TaskHelper::getTotalByGroups($group->id);
        
        //dd($cashbygroup);

        if($cashbygroup[0]['dr_amount'] == null && $cashbygroup[0]['dr_amount'] == null){

                echo '<tr>
                   
                    <td class="moto">'.$sub_mark.$group->code.'</td>
                    <td class="moto">'.$group->name.'</td> 
                    <td class="moto">'.'0.00'.'</td>
                    <td class="moto">'.'0.00'.'</td>
                    <td class="moto">'.'0.00'.'</td>
                    <td class="moto">'.'0.00'.'</td>
                   </tr>';

        }else{

            if($cashbygroup[0]['dr_amount']>$cashbygroup[0]['cr_amount']){

                  echo '<tr>
                       
                        <td class="moto ">'.$sub_mark.$group->code.'</td>
                        <td class="moto">'.$group->name.'</td> 
                        <td class="moto">'.number_format($cashbygroup[0]['opening_balance'],2).'</td>
                        <td class="moto">Dr '.number_format($cashbygroup[0]['dr_amount'],2).'</td>
                        <td class="moto">Cr '.number_format($cashbygroup[0]['cr_amount'],2).'</td>
                        <td class="moto">Dr '.number_format(abs($cashbygroup[0]['dr_amount']-$cashbygroup[0]['cr_amount']),2).'</td>
                       </tr>';

               }elseif( $cashbygroup[0]['dr_amount'] < $cashbygroup[0]['cr_amount'] ){

                  echo '<tr>
                   
                    <td class="moto">'.$sub_mark.$group->code.'</td>
                    <td class="moto">'.$group->name.'</td> 
                    <td class="moto">'.number_format($cashbygroup[0]['opening_balance'],2).'</td>
                    <td class="moto">Dr '.number_format($cashbygroup[0]['dr_amount'],2).'</td>
                    <td class="moto">Cr '.number_format($cashbygroup[0]['cr_amount'],2).'</td>
                    <td class="moto">Cr '.number_format(abs($cashbygroup[0]['dr_amount']-$cashbygroup[0]['cr_amount']),2).'</td>
                   </tr>';

               }else{

                 echo '<tr>
                    <td class="moto">'.$sub_mark.$group->code.'</td>
                    <td class="moto">'.$group->name.'</td> 
                    <td class="moto">'.number_format($cashbygroup[0]['opening_balance'],2).'</td>
                    <td class="moto">Dr '.number_format($cashbygroup[0]['dr_amount'],2).'</td>
                    <td class="moto">Cr '.number_format($cashbygroup[0]['cr_amount'],2).'</td>
                    <td class="moto">Dr '.number_format(abs($cashbygroup[0]['dr_amount']-$cashbygroup[0]['cr_amount']),2).'</td>
                   </tr>';


               }

        }

        $ledgers= \App\Models\COALedgers::orderBy('code', 'asc')->where('org_id',auth()->user()->org_id)->where('group_id',$group->id)->get(); 

        if(count($ledgers)>0){
            $submark= $sub_mark;
            $sub_mark = $sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 

              foreach($ledgers as $ledger){

                // $closing_balance =TaskHelper::getLedgerDebitCredit($ledger->id);
                $closing_balance =TaskHelper::getLedgerTotal($ledger->id);
                if ($closing_balance['amount'] > 0) {
                  if( $closing_balance['dc'] == 'D'){

                     echo '<tr style="color: #3c8dbc;">
                            
                            <td class="text-success"><a href="'.route('admin.chartofaccounts.detail.ledgers', $ledger->id).'" style="color:#3c8dbc;">'.$sub_mark.$ledger->code.'</a></td>
                            <td class="text-success"><a href="'.route('admin.chartofaccounts.detail.ledgers', $ledger->id).'" style="color:#3c8dbc;">'.$ledger->name.'</a></td>
                            <td class="text-success">'.number_format($ledger->op_balance,2).'</td>

                            <td class="text-success">Dr '.number_format($closing_balance['amount'],2).'</td>
                            <td class="text-success">Cr 0.00</td>
                            <td class="text-success">Dr '.number_format($closing_balance['amount'],2).'</td>

                       </tr>';
                  }
                  else{

                    echo '<tr style="color: #3c8dbc;">
                     
                        <td class=""><a href="'.route('admin.chartofaccounts.detail.ledgers', $ledger->id).'" style="color:#3c8dbc;">'.$sub_mark.$ledger->code.'</a></td>
                        <td class=""><a href="'.route('admin.chartofaccounts.detail.ledgers', $ledger->id).'" style="color:#3c8dbc;">'.$ledger->name.'</a></td>
                        <td class="">'.number_format($ledger->op_balance,2).'</td>
                        <td class="">Dr 0.00</td>
                        <td class="">Cr '.number_format($closing_balance['amount'],2).'</td>
                        <td class="">Cr '.number_format($closing_balance['amount'],2).'</td>
                       </tr>';
                  }
                  
                }

              
           }
           $sub_mark=$submark;
        }


        CategoryTree($group->id,$sub_mark."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"); 
      }
    }

  }
?>

    
</head><body>



    <header class="clearfix">

	 <table>
	          <TR><TD width="50%" style="float:left">
	      <div id="logo">
	        <img src="{{public_path()}}/org/{{$imagepath}}">
	      </div>
	    </TD><td>

	      <div width="50%" style="text-align: right">
	         <h3 class="name">{{ \Auth::user()->organization->organization_name }} </h3>
	                <div>{{ \Auth::user()->organization->address }}</div>
	                  Phone: {{ \Auth::user()->organization->phone }}<br>
	                  Email: {{ \Auth::user()->organization->email }}<br/>
	                <div>PAN: {{ \Auth::user()->organization->vat_id }}</div>
	      </div>
	    </td></TR>
	  </table>
    </header>
    <main>


      <div id="details" class="clearfix">

        <table>
          <TR>
          	<TD>
		        <div id="client">
		          <div class="address">Trial Balance Report<br/></div>
		        </div>
            </TD>
          </TR>
        </table>
      <table border="0" cellspacing="0" cellpadding="0" style="width: 100%;">
        <thead>
          <tr>
            <th>Account Code</th>
            <th>Account Name</th>
            <th>Opening Balance</th>
            <th>Debit Total({{env('APP_CURRENCY')}})</th>
            <th>Credit Total({{env('APP_CURRENCY')}})</th>
            <th>Closing Balance</th>
          </tr>
        </thead>
        <tbody>
           {{ CategoryTree() }}
            <tr style=" font-size: 16.5px; font-weight: bold;">

          <?php

              $assetstotal = \TaskHelper::getTotalByGroups(1);
              $equitytotal = \TaskHelper::getTotalByGroups(2);
              $incometotal = \TaskHelper::getTotalByGroups(3);
              $expensestotal = \TaskHelper::getTotalByGroups(4);

              $total_dr_amount = $assetstotal[0]['dr_amount'] + $equitytotal[0]['dr_amount'] + $incometotal[0]['dr_amount'] + $expensestotal[0]['dr_amount'];

              $total_cr_amount =  $assetstotal[0]['cr_amount'] + $equitytotal[0]['cr_amount'] + $incometotal[0]['cr_amount'] + $expensestotal[0]['cr_amount'];

           ?>
             <td colspan="3"></td>
             <td style="font-weight: 25px">Dr {{ number_format($total_dr_amount,2)}}</td>
             <td style="font-weight: 25px">Cr {{number_format($total_cr_amount,2)}} </td>
             <td style="font-weight: 25px">
              @if($total_dr_amount == $total_cr_amount)
                   <i class="fa fa-check-circle"></i>
             @else
                   <i class="fa fa-close"></i>
              @endif
             </td>
         </tr>
        </tbody>
      </table>
    </main>
   <!--  <footer>
      This Statement was created on MEROCRM.
    </footer> -->
</body></html>