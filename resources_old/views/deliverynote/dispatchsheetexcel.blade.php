<table style="border: 1px solid; text-align:center">
    <tr>
        <th colspan="16" style="text-align:center; font-size:16px font-weight:bold;">Jai Shreebinayak Distributor and Trade Link Pvt.Ltd</th>
    </tr>
    <tr>    
        <th colspan="16" style="text-align:center; font-size:14px font-weight:800;">Chamati-15, Kathmandu, Nepal</th>
    </tr>
    <tr>
        <th colspan="16" style="text-align:center; font-size:14px font-weight:800;">Daily Dispatch Sheet and Stock</th>
    </tr>    
   
</table>

<div class="row">

    <table class="table table-bordered">
        <?php
        
        $totaldispatch = 0;
        $totalpurchase = 0;
        $totalopeningvalue = 0;
        $totalasopening=0;
        $totalclosingvalue = 0;
        $totalasclosing=0;
    
        ?>
        <tr>
            <th scope="col" style="font-weight: 700; font-size:16px">S.N</th>
            <th scope="col" style="font-weight: 700; font-size:16px">Outlet Name</th>
            <th scope="col" style="font-weight: 700; font-size:16px">Address</th>
            @foreach ($products as $product)
                <th style="font-weight: 700; font-size:16px">{{ $product->name }}</th>
            @endforeach
            <th style="font-weight: 700; font-size:16px">Total</th>

        </tr>

        <tr>
            @foreach ($perdaysalesdetails as $key => $details)
          
                <?php
                $outletsalestotal=0;
                $Client = \App\Models\Client::where('id', $key)
                    ->select('name', 'physical_address')
                    ->first();
                ?>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $Client->name }}</td>
                <td>{{ $Client->physical_address }}</td>
                @foreach ($products as $product)
                    <td>
                        <?php
                        $outletsalestotal+=(double)abs($details->where('client_id', $key)->where('product_id', $product->id)->first()->totalsalesqty);
                            ?>
                        {{abs($details->where('client_id', $key)->where('product_id', $product->id)->first()->totalsalesqty ?? 0) }}
                    </td>
                @endforeach
                <td>{{$outletsalestotal}}</td>
        </tr>
     @endforeach
     <tr>
        <td></td>
        <td>Opening Stock</td>
        <td>-</td>
        @foreach($products as $product)
      <?php
      $openingvalue=0;
      ?>
      
       @if(isset($asopeningstock[$product->id][0]->asopeningstock)&&($asopeningstock[$product->id][0]->asopeningstock>0 || $asopeningstock[$product->id][0]->asopeningstock==0))    
       @if($totalremaining[$product->id][0]->totalremainingqty== null || $totalremaining[$product->id][0]->totalremainingqty== "" )
                <?php 
                $openingvalue=(($asopeningstock[$product->id][0]->asopeningstock/$product->unit->qty_count) + 0);
            ?>
           @else
           <?php 
           $openingvalue=(($asopeningstock[$product->id][0]->asopeningstock/$product->unit->qty_count) + $totalremaining[$product->id][0]->totalremainingqty);
           ?>
          @endif
          <?php (double)$openingvalue;
            $totalopeningvalue+=$openingvalue;
          ?>
        <td>{{$openingvalue}}</td>
        @elseif(isset($foropening[$product->id][0]->removestock)&&($foropening[$product->id][0]->removestock<0 ||$foropening[$product->id][0]->removestock>0 || $foropening[$product->id][0]->removestock == 0))
                @if($totalremaining[$product->id][0]->totalremainingqty== null || $totalremaining[$product->id][0]->totalremainingqty== "" )
                
                    <?php 
                    $openingvalue=(($foropening[$product->id][0]->removestock/$product->unit->qty_count) + 0);
                    ?>
                @else
                    <?php 
                    $openingvalue=(($foropening[$product->id][0]->removestock/$product->unit->qty_count) + $totalremaining[$product->id][0]->totalremainingqty);
                    ?>
            @endif
            <?php
            $totalopeningvalue+=$openingvalue;
            ?>

        <td>{{$openingvalue}}</td>
        @elseif($totalremaining[$product->id][0]->totalremainingqty)
       
            <?php
                $totalopeningvalue+=$totalasopening+$totalremaining[$product->id][0]->totalremainingqty
            ?>
            <td>{{$totalremaining[$product->id][0]->totalremainingqty}}</td>
        @else
        <td>0</td>   
        @endif 
        @endforeach
        <td>{{$totalopeningvalue}}</td>
     </tr>
      <tr>
        <td></td>
        <td>Purchase From Company</td>
        <td></td>
        
            @foreach($products as $product)
            @if($perdaypurchasedetail[$product->id][0]->totalpurchaseqty)
            <?php
                $totalpurchase+=(double)$perdaypurchasedetail[$product->id][0]->totalpurchaseqty;
            ?>
            <td>
                {{$perdaypurchasedetail[$product->id][0]->totalpurchaseqty}}
            </td>
            @else
            <td>0</td>    
            @endif
   
     @endforeach
     <td>{{$totalpurchase}}</td>
    </tr>  
    <tr>
        <td></td>
        <td>Total Dispatch</td>
        <td></td>
        @foreach($products as $product)
        @if($perdayproducttotal[$product->id][0]->totalproductqty)
        <?php
            $totaldispatch+=(double)abs($perdayproducttotal[$product->id][0]->totalproductqty);
        ?>
        <td>

            {{abs($perdayproducttotal[$product->id][0]->totalproductqty)}}
        </td>
        @else
        <td>0</td>    
        @endif

 @endforeach
 <td>{{$totaldispatch}}</td>
    </tr>
<tr>
    <td></td>
    <td>Closing Stock</td>
    <td></td>
    @foreach($products as $product)
      <?php
        $openingvalue=0;
        $finalclosingvalue=0;
        ?>
       @if(isset($asopeningstock[$product->id][0]->asopeningstock)&&($asopeningstock[$product->id][0]->asopeningstock>0 || $asopeningstock[$product->id][0]->asopeningstock==0))
       @if($totalremaining[$product->id][0]->totalremainingqty== null || $totalremaining[$product->id][0]->totalremainingqty== "" )
           <?php 
           $openingvalue=(($asopeningstock[$product->id][0]->asopeningstock/$product->unit->qty_count) + 0);
       ?>
      @else
      <?php 
      $openingvalue=(($asopeningstock[$product->id][0]->asopeningstock/$product->unit->qty_count) + $totalremaining[$product->id][0]->totalremainingqty);
      ?>
     @endif
     <?php (double)$openingvalue;
       $totalopeningvalue+=$openingvalue;
     ?>
   @elseif($foropening[$product->id][0]->removestock<0 ||$foropening[$product->id][0]->removestock>0 || $foropening[$product->id][0]->removestock == 0)
  
           @if($totalremaining[$product->id][0]->totalremainingqty== null || $totalremaining[$product->id][0]->totalremainingqty== "" )
               <?php 
               $openingvalue=(($foropening[$product->id][0]->removestock/$product->unit->qty_count) + 0);
               ?>
           @else
               <?php 
               $openingvalue=(($foropening[$product->id][0]->removestock/$product->unit->qty_count) + $totalremaining[$product->id][0]->totalremainingqty);
               ?>
       @endif
       <?php
       $totalopeningvalue+=$openingvalue;
       ?>
   @elseif($totalremaining[$product->id][0]->totalremainingqty)
  
       <?php
           $totalopeningvalue+=$totalasopening+$totalremaining[$product->id][0]->totalremainingqty
       ?>
       <td>{{$totalremaining[$product->id][0]->totalremainingqty}}</td>
   @else
   <?php
        $openingvalue= 0;
   ?>
   @endif 
        @if($openingvalue &&  $openingvalue!="")
            @if($perdaypurchasedetail[$product->id][0]->totalpurchaseqty== null || $perdaypurchasedetail[$product->id][0]->totalpurchaseqty== "" )
                <?php 
                $finalclosingvalue=($openingvalue + 0 - abs($perdayproducttotal[$product->id][0]->totalproductqty??0));
                ?>
            @else
            <?php 
            $finalclosingvalue=($openingvalue + $perdaypurchasedetail[$product->id][0]->totalpurchaseqty) - abs($perdayproducttotal[$product->id][0]->totalproductqty??0);
            ?>
            @endif
            <?php $totalasclosing+= (double)$finalclosingvalue;
            $totalclosingvalue+= (double)$finalclosingvalue?>
            <td>{{$finalclosingvalue}}</td>
        @elseif($perdaypurchasedetail[$product->id][0]->totalpurchaseqty)
            <?php
                $totalclosingvalue+=$totalasclosing+$perdaypurchasedetail[$product->id][0]->totalpurchaseqty - abs($perdayproducttotal[$product->id][0]->totalproductqty??0)
            ?>
            <td>{{$perdaypurchasedetail[$product->id][0]->totalpurchaseqty- abs($perdayproducttotal[$product->id][0]->totalproductqty??0)}}</td>
        @else
            <td>0</td>   
        @endif 
    @endforeach
    <td>{{$totalclosingvalue}}</td>
   </tr>

    </table>
</div><!-- /.row -->