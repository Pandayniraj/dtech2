@extends('layouts.master')
@section('content')
    <style type="text/css">
        .balancesheettable th,.balancesheettable td{


            padding: 4px !important;

        }
        table{
            font-size: 12px;
        }
        /*.f-16{*/
        /*    font-size: 16.5px;*/
        /*}*/
    </style>

    <link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}" rel="stylesheet" type="text/css" />

    <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
        <h1>
            {{ $page_title }}
            <small>  {!! $page_description ?? "Page description" !!}</small>
        </h1>
        {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
{{--        <br>--}}
{{--        <a href="{{route('admin.accounts.reports.balancesheet.pdf')}}" class="btn btn-primary">PDF</a>--}}
{{--    <!-- <a href="{{route('admin.accounts.reports.balancesheet.excel')}}" class="btn btn-primary">Excel</a> -->--}}
{{--        <br>--}}
    </section>
       <div class='card'>
        <div class="card-body pt-6">
            @php
                $GLOBALS['profit_before_tax']=0;
                $income = \TaskHelper::getDrCrByGroups(\FinanceHelper::get_ledger_id('INCOME_LEDGER_GROUP'), $start_date, $end_date);
                $return = \TaskHelper::getDrCrByGroups(\FinanceHelper::get_ledger_id('SALES_RETURN_GROUP'), $start_date, $end_date);
                $total_income = abs($income['dr_amount']-$income['cr_amount']) - abs($return['dr_amount']-$return['cr_amount']);
                $cogs = \TaskHelper::getDrCrByGroups($group_id = \FinanceHelper::get_ledger_id('COST_OF_GOODS_GROUP'), $start_date, $end_date);
                $cost_of_sales = abs($cogs['dr_amount']-$cogs['cr_amount']);
                $directexpenses = \TaskHelper::getDrCrByGroups($group_id = \FinanceHelper::get_ledger_id('COST_OF_DIRECT_EXPENSES'), $start_date, $end_date);
                $cost_of_directexpenses = abs($directexpenses['dr_amount']-$directexpenses['cr_amount']);
                $gross_profit = $total_income - $cost_of_sales - $cost_of_directexpenses;

                $group_id = \FinanceHelper::get_ledger_id('INDIRECT_INCOME_LEDGER_GROUP');
                $other_income_totals = \TaskHelper::getDrCrByGroups($group_id, $start_date, $end_date);
                $other_income = abs($other_income_totals['dr_amount']-$other_income_totals['cr_amount']);

                $indirect_expense_groups = \App\Models\COAgroups::where('parent_id', \FinanceHelper::get_ledger_id('INDIRECT_EXPENSES_LEDGER_GROUP'))->get();
                $indirect_exp_total = 0;
                foreach($indirect_expense_groups as $group){
                    $expenses = \TaskHelper::getDrCrByGroups($group->id, $start_date, $end_date);
                    $indirect_exp_total += abs($expenses['dr_amount']-$expenses['cr_amount']);
                }
                $profit_before_tax = $gross_profit + $other_income - $indirect_exp_total;
                $GLOBALS['profit_before_tax']=$profit_before_tax;

                function CategoryTree($parent_id = null, $sub_mark = '', $actype, $start_date, $end_date)
                {
                    $total = 0;
                    $groups = \App\Models\COAgroups::orderBy('code', 'asc')->where('parent_id', $parent_id)
                        ->where('org_id', auth()->user()->org_id)->get();

                    if (count($groups) > 0) {
                        foreach ($groups as $key=>$group) {
                            $cashbygroup = \TaskHelper::getTotalByGroups($group->id, $start_date, $end_date);

                            if ($cashbygroup['dr_amount'] == null && $cashbygroup['cr_amount'] == null && $cashbygroup['opening_balance']['amount'] == 0) {
                                echo '<tr>
                                        <td onclick="{{if(' . $group->children->count() . '<1)' . 'getLedgersFromAjax(this,' . $group->id . ')' . '}}">
                                        <b>' . $sub_mark . '[' . $group->code . ']' . $group->name . (($group->children->count() < 1) ?
                                            ' <i class="fa fa-chevron-down" style="font-size: 10px"></i>' : '') . '</b></td>
                                        <td><b><span>0.00</span></b></td>
                                     </tr>';
                            } else {
                                $sum = $cashbygroup['dr_amount'] - $cashbygroup['cr_amount'];
                                $closing_balance = $cashbygroup['opening_balance']['dc'] == 'D' ? ($sum + $cashbygroup['opening_balance']['amount']) :
                                    ($sum - $cashbygroup['opening_balance']['amount']);
                                if ($group->id == \FinanceHelper::get_ledger_id('SHARE_CAPITAL_RESERVES')) {
                                    $closing_balance = $closing_balance + $GLOBALS['profit_before_tax'];
                                }

                                if ($closing_balance > 0) {
                                    echo '<tr>
                                        <td onclick="{{if(' . $group->children->count() . '<1)' . 'getLedgersFromAjax(this,' . $group->id . ')' . '}}">
                                        <b>' . $sub_mark . '[' . $group->code . ']' . $group->name . (($group->children->count() < 1) ?
                                            ' <i class="fa fa-chevron-down" style="font-size: 10px"></i>' : '') . '</b></td>
                                        <td><b><span>Dr ' . number_format(abs($closing_balance), 2) . '</span></b></td>
                                    </tr>';
                                } else {
                                    echo '<tr>
                                         <td onclick="{{if(' . $group->children->count() . '<1)' . 'getLedgersFromAjax(this,' . $group->id . ')' . '}}">
                                         <b>' . $sub_mark . '[' . $group->code . ']' . $group->name . (($group->children->count() < 1) ?
                                            ' <i class="fa fa-chevron-down" style="font-size: 10px"></i>' : '') . '</b></td>
                                        <td><b><span>Cr ' . number_format(abs($closing_balance), 2) . '</span></b></td>
                                    </tr>';
                                }
                            }

                            if ($group->id == \FinanceHelper::get_ledger_id('SHARE_CAPITAL_RESERVES')) {
                                if ($GLOBALS['profit_before_tax'] < 0) {
                                    echo '<tr>
                                        <td><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Profit Before Tax (Current Year)</b></td>
                                        <td><b><span>Dr ' . number_format(abs($GLOBALS['profit_before_tax']), 2) . '</span></b></td>
                                    </tr>';
                                } else {
                                    echo '<tr>
                                        <td><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Profit Before Tax (Current Year)</b></td>
                                        <td><b><span>Cr ' . number_format(abs($GLOBALS['profit_before_tax']), 2) . '</span></b></td>
                                    </tr>';
                                }
                            }

                            CategoryTree($group->id, $sub_mark . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $actype, $start_date, $end_date);
                        }
                    }
                }
            @endphp

            <form method="get" action="/admin/accounts/reports/balancesheet">
                <div class="row col-md-12">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Start Date</label>
                            <div class="input-group">
                                <input id="ReportStartdate" type="date" name="start_date" class="form-control input-sm"
                                       value="{{ $start_date }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>End Date</label>
                            <div class="input-group">
                                <input id="ReportEnddate" type="date" name="end_date" class="form-control input-sm"
                                       value="{{ $end_date }}">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2" style="margin-top: 23px;">
                        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                        <a href="#" class="btn btn-success btn-sm">Excel</a>
                    </div>
                </div>
            </form>
        </div>

        <div class='row mt-5'>
            <div class='col-md-12'>
                <div class="box">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered balancesheettable">
                                <thead>
                                <tr class="bg-light-primary">
                                    <th><b>Assets </b></th>
                                    <th>Amount(Rs)</th>
                                </tr>
                                </thead>
                                <tbody>
                                {{ CategoryTree(1,'','assets',$start_date,$end_date) }}

                                <tr style=" font-size: 13px; font-weight: bold; background-color: silver">
                                    <th>Total Assets</th>
                                    <td>
                                        <?php
                                        $asset_total = \TaskHelper::getTotalByGroups(1, $start_date, $end_date);
                                        $sum = $asset_total['dr_amount'] - $asset_total['cr_amount'];
                                        $asset_closing_balance = $asset_total['opening_balance']['dc'] == 'D' ? ($sum + $asset_total['opening_balance']['amount']) :
                                            ($sum - $asset_total['opening_balance']['amount']);
                                        ?>
                                        {{$asset_closing_balance>0?'Dr '.number_format($asset_closing_balance,2):'Cr '.number_format(abs($asset_closing_balance),2)}}
                                    </td>
                                </tr>
                                </tbody>
                                <tfoot>

                                </tfoot>

                                <thead>
                                <tr class="bg-light-success">
                                    <th><b>Liabilities and Owners Equity (Cr)</b></th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                {{ CategoryTree(2,'','libalities',$start_date,$end_date) }}
                                </tbody>
                                <tfoot>
                                <tr style=" font-size: 13px; font-weight: bold; background-color: silver">
                                    <td>Total Liabilities and Owners Equity</td>
                                    <td> <?php
                                         $liabilities_total = \TaskHelper::getTotalByGroups(2, $start_date, $end_date);
                                         $sum = $liabilities_total['dr_amount'] - $liabilities_total['cr_amount'];
                                         $liab_closing_balance = $liabilities_total['opening_balance']['dc'] == 'D' ? ($sum + $liabilities_total['opening_balance']['amount']) :
                                             ($sum - $liabilities_total['opening_balance']['amount']);
                                         $total_liabilities = $liab_closing_balance > 0 ? $liab_closing_balance + $GLOBALS['profit_before_tax'] : $liab_closing_balance - $GLOBALS['profit_before_tax'];
                                         ?>
                                        {{$liab_closing_balance>0?'Dr '.number_format($total_liabilities,2):'Cr '.number_format(abs($total_liabilities),2)}}
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

<!-- Optional bottom section for modals etc... -->
@section('body_bottom')

<script type="text/javascript">
    $(function () {
        const allTotal = {
            drassets: 0,
            drassets1: 0,
            crassets: 0,
            drlibalities: 0,
            crlibalities: 0,

        }
        $('.drassets').each(function () {
            var number = $(this).text().replace(/\,/g, '')
            allTotal.drassets += Number(number);

        });

        $('.crassets').each(function () {
            var number = $(this).text().replace(/\,/g, '')

            allTotal.crassets += Number(number);

        });

        $('.drlibalities').each(function () {
            var number = $(this).text().replace(/\,/g, '')

            allTotal.drlibalities += Number(number);


        });

        $('.crlibalities').each(function () {
            var number = $(this).text().replace(/\,/g, '')

            allTotal.crlibalities += Number(number);

        });

        var assetsTotal = allTotal.crassets - allTotal.drassets;
        var libalitiesTotal = allTotal.drlibalities - allTotal.crlibalities;

        if ((assetsTotal) >= 0) {
            $('.assetsTotal').text('Cr ' + assetsTotal.toFixed(3));
        } else {
            var assetsTotal = -assetsTotal;
            $('.assetsTotal').text('Dr ' + assetsTotal.toFixed(3));
        }

        if (libalitiesTotal >= 0) {
            $('#libalitiesTotal').text('Dr ' + libalitiesTotal.toFixed(3));
        } else {
            var libalitiesTotal = -libalitiesTotal;
            $('#libalitiesTotal').text('Cr ' + libalitiesTotal.toFixed(3));
        }


        console.log(allTotal.crassets - allTotal.drassets);


        $('#netProfit').text(((allTotal.crassets - allTotal.drassets) - (allTotal.drlibalities - allTotal.crlibalities)).toFixed(3));
    });
</script>

<script type="text/javascript">
    $(function () {
        $('.datepicker').datepicker({
            //inline: true,
            dateFormat: 'yy-mm-dd',
            sideBySide: false,
        });
    });

    $(document).on('change', '#fiscal_year_id', function () {
        var fiscal_year = $(this).val()
        var fiscal_detail = ''
        var all_fiscal_years = {!! json_encode($allFiscalYear); !!}
        all_fiscal_years.forEach((item) => {
            if (item.fiscal_year == fiscal_year)
                fiscal_detail = item
        });

        $('#ReportStartdate').val(fiscal_detail.start_date)
        $('#ReportEnddate').val(fiscal_detail.end_date)

    })
</script>

<script>
    var disabled = false;

    function getLedgersFromAjax(el, group_id) {
        if (disabled)
            return
        disabled = true
        $(el).find('i').show()
        $(el).attr('disabled', true)
        $.ajax({
            url:"{{ route('admin.chartofaccounts.get-BalanceSheet-LedgersAjax') }}",
            data: {
                group_id: group_id,
                start_date: "{{$start_date}}",
                end_date: "{{$end_date}}",
                "_token": "{{ csrf_token() }}"
            },
            type: "POST",
            dataType: "json",
            success: function (data) {
                $(el).closest('tr').after(data);
                $(el).closest('tr').after(data);
                $(el).find('i').hide()
                $(el).removeAttr('disabled')
                disabled = false
            },
            error: function (error) {
                $(el).find('i').hide();
                $(el).removeAttr('disabled');
                disabled = false;
            }
        })
    }
</script>
@endsection
