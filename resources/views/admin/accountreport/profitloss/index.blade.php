@extends('layouts.master')
@section('content')

    <link href="{{ asset("/bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.css") }}"
          rel="stylesheet" type="text/css"/>
    <style>
        tr td, tr th {
            border: 1px solid #dbdada !important;
        }
    </style>
    <section class="content-header" style="margin-top: -35px; margin-bottom: 20px">
        <h1>
            {{ $page_title }}
            <small>  {!! $page_description ?? "Page description" !!}</small>
        </h1>
        {!! MenuBuilder::renderBreadcrumbTrail(null, 'root', false)  !!}
    </section>
    <div class="row">
        <form method="get" action="/admin/accounts/reports/profitloss">
            <div class="col-md-2">
                <div class="form-group">
                    <label>Start Date</label>
                    <div class="input-group">
                        <input id="ReportStartdate" type="text" name="start_date"
                               class="form-control input-sm datepicker" value="{{ $start_date }}">
                        <div class="input-group-addon">
                            <i>
                                <div class="fa fa-info-circle" data-toggle="tooltip"
                                     title="Note : Leave start date as empty if you want statement from the start of the financial year.">
                                </div>
                            </i>
                        </div>
                    </div>
                    <!-- /.input group -->
                </div>
                <!-- /.form group -->
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>End Date</label>
                    <div class="input-group">
                        <input id="ReportEnddate" type="text" name="end_date" class="form-control input-sm datepicker"
                               value="{{ $end_date }}">
                        <div class="input-group-addon">
                            <i>
                                <div class="fa fa-info-circle" data-toggle="tooltip"
                                     title="Note : Leave end date as empty if you want statement till the end of the financial year.">
                                </div>
                            </i>
                        </div>
                    </div>
                    <!-- /.input group -->
                </div>
                <!-- /.form group -->
            </div>

            <div style="padding: 0;display: inline-block">
                <div>
                    <label for="">
                        <label for=""></label>
                    </label>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                <a href="{{route('admin.accounts.reports.profitloss.excel')}}" class="btn btn-success btn-sm">Excel</a>


            </div>

    </div>

    <div class="box">
        <div class='row'>
            <div class='col-md-8'>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" id="orders-table">
                            <thead>
                            <tr class="bg-blue">

                                <th>Particulars</th>

                                <th>Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th colspan="2" id="myTree">Income</th>
                            </tr>


                            <?php
                            $ledger_table = new \App\Models\COALedgers();
                            $prefix = '';



                                $current_fiscal = \App\Models\Fiscalyear::where('current_year', 1)->first();
                                $fiscal_year = \Session::get('selected_fiscal_year') ?? $current_fiscal->numeric_fiscal_year;
                                if ($fiscal_year != $current_fiscal->numeric_fiscal_year) {
                                    $prefix = $fiscal_year . '_';
                                    $new_ledger = $prefix . 'coa_ledgers';
                                    $ledger_table->setTable($new_ledger);
                                }

                            $group_id = \FinanceHelper::get_ledger_id('INCOME_PARENT_LEDGER_GROUP');
                                // dd($group_id);
                            $income = \TaskHelper::getDrCrByGroups($group_id, $start_date, $end_date);

                            ?>


                            <tr style="font-style:italic;font-weight: 600;cursor: pointer"
                                data-target=".ledgers{{$group_id}}" class="text-success accordion-toggle"
                                data-toggle="collapse">
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;Sales Revenue <i class="fa fa-chevron-down"
                                                                             style="font-size: 12px"></i>
                                </td>


                                <td>

                                    {{number_format($income['cr_amount'],2)}}</td>
                            </tr>
                            <?php
                            $ledgers = $ledger_table->where('group_id', $group_id)->get();

                            ?>


                            @foreach($ledgers as $ledger)
                                <?php
                                $dr_cr = TaskHelper::getLedgerDrCr($ledger, $start_date, $end_date);
                                ?>
                                <tr style="cursor: pointer"
                                    class="hiddenRow accordian-body collapse ledgers{{$ledger->group_id}}">
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <a style="font-weight:bold;font-size:13px"
                                           href="/admin/accounts/reports/ledger_statement?ledger_id={{$ledger->id}}">
                                            {{$ledger->name}}</a></td>
                                    <td>
                                        <?php
                                        ?>
                                        {{number_format($dr_cr['cr_total'],2)}}</td>
                                </tr>
                            @endforeach



                            <?php
                            $group_id = \FinanceHelper::get_ledger_id('SALES_RETURN_GROUP');
                            $return = \TaskHelper::getDrCrByGroups($group_id, $start_date, $end_date);
                            $total_income = $income['cr_amount'] - $return['dr_amount'];
                            ?>
                            <tr style="font-style:italic;font-weight: 600;cursor: pointer"
                                data-target=".ledgers{{$group_id}}" class="text-danger accordion-toggle"
                                data-toggle="collapse">
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;Sales Return <i class="fa fa-chevron-down"
                                                                            style="font-size: 12px"></td>
                                <td>

                                    -{{number_format($return['dr_amount'],2)}}</td>
                            </tr>
                            <?php

                            $ledgers = $ledger_table->where('group_id', $group_id)->get();

                            ?>
                            @foreach($ledgers as $ledger)
                                <?php
                                $dr_cr = TaskHelper::getLedgerDrCr($ledger, $start_date, $end_date);

                                ?>
                                <tr style="cursor: pointer"
                                    class="hiddenRow accordian-body collapse ledgers{{$ledger->group_id}}">
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <a style="font-weight:bold;font-size:13px"
                                           href="/admin/accounts/reports/ledger_statement?ledger_id={{$ledger->id}}">
                                            {{$ledger->name}}</a></td>
                                    <td>
                                        {{number_format($dr_cr['dr_total'],2)}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <th>Total Income</th>
                                <th>{{number_format($total_income,2)}}</th>
                            </tr>
                            <?php
                            $group_id = \FinanceHelper::get_ledger_id('COST_OF_GOODS_GROUP');
                            $cogs = \TaskHelper::getDrCrByGroups($group_id, $start_date, $end_date);
                            $cost_of_sales = $cogs['dr_amount']-$cogs['cr_amount'];
                            ?>
                            <tr style="font-style:italic;font-weight: 600;cursor: pointer"
                                data-target=".ledgers{{$group_id}}" class="text-danger accordion-toggle"
                                data-toggle="collapse">
                                <td>Cost of Goods Sold <i class="fa fa-chevron-down" style="font-size: 12px"></td>

                                <td>{{number_format($cost_of_sales,2)}}</td>
                            </tr>
                            <?php
                            $ledgers = $ledger_table->where('group_id', $group_id)->get();
                            ?>
                            @foreach($ledgers as $ledger)
                                <?php
                                $dr_cr = TaskHelper::getLedgerDrCr($ledger, $start_date, $end_date);

                                ?>
                                <tr style="cursor: pointer"
                                    class="hiddenRow accordian-body collapse ledgers{{$ledger->group_id}}">
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <a style="font-weight:bold;font-size:13px"
                                           href="/admin/accounts/reports/ledger_statement?ledger_id={{$ledger->id}}">
                                            {{$ledger->name}}</a></td>
                                    <td>
                                        <?php
                                        ?>
                                        {{number_format($dr_cr['dr_total']-$dr_cr['cr_total'],2)}}</td>
                                </tr>
                            @endforeach
                            <?php
                            $group_id = \FinanceHelper::get_ledger_id('COST_OF_DIRECT_EXPENSES');
                            $directexpenses = \TaskHelper::getDrCrByGroups($group_id, $start_date, $end_date);
                            $cost_of_directexpenses = $directexpenses['dr_amount']-$directexpenses['cr_amount'];
                            ?>
                            <tr style="font-style:italic;font-weight: 600;cursor: pointer"
                                data-target=".ledgers{{$group_id}}" class="text-danger accordion-toggle"
                                data-toggle="collapse">
                                <td>Direct Expenses<i class="fa fa-chevron-down" style="font-size: 12px"></td>

                                <td>{{number_format($cost_of_directexpenses,2)}}</td>
                            </tr>
                            <?php
                            $ledgers = $ledger_table->where('group_id', $group_id)->get();
                            ?>
                            @foreach($ledgers as $ledger)
                                <?php
                                $dr_cr = TaskHelper::getLedgerDrCr($ledger, $start_date, $end_date);

                                ?>
                                <tr style="cursor: pointer"
                                    class="hiddenRow accordian-body collapse ledgers{{$ledger->group_id}}">
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <a style="font-weight:bold;font-size:13px"
                                           href="/admin/accounts/reports/ledger_statement?ledger_id={{$ledger->id}}">
                                            {{$ledger->name}}</a></td>
                                    <td>
                                        <?php
                                        ?>
                                        {{number_format($dr_cr['dr_total']-$dr_cr['cr_total'],2)}}</td>
                                </tr>
                            @endforeach
                            <tr class="bg-info">
                                <th>Gross Profit</th>
                                <?php
                                $gross_profit = $total_income - $cost_of_sales - $cost_of_directexpenses;
                                ?>
                                <th>{{number_format($gross_profit,2)}}</th>
                            </tr>
                            <?php
                            $group_id = \FinanceHelper::get_ledger_id('INDIRECT_INCOME_LEDGER_GROUP');
                            $other_income_totals = \TaskHelper::getDrCrByGroups($group_id, $start_date, $end_date);
                            $other_income = abs($other_income_totals['dr_amount']-$other_income_totals['cr_amount']);
                            ?>
                            <tr style="font-style:italic;font-weight: 600;cursor: pointer"
                                data-target=".ledgers{{$group_id}}" class="text-success accordion-toggle"
                                data-toggle="collapse">
                                <td>Other Incomes <i class="fa fa-chevron-down" style="font-size: 12px"></td>

                                <td>{{number_format($other_income,2)}}</td>
                            </tr>
                            <?php
                            $ledgers = $ledger_table->where('group_id', $group_id)->get();

                            ?>
                            @foreach($ledgers as $ledger)
                                <?php
                                $dr_cr = TaskHelper::getLedgerDrCr($ledger, $start_date, $end_date);

                                ?>
                                <tr style="cursor: pointer"
                                    class="hiddenRow accordian-body collapse ledgers{{$ledger->group_id}}">
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;
                                        <a style="font-weight:bold;font-size:13px"
                                           href="/admin/accounts/reports/ledger_statement?ledger_id={{$ledger->id}}">
                                            {{$ledger->name}}</a></td>
                                    <td>
                                        <?php
                                        ?>
                                        {{number_format(abs($dr_cr['dr_total']-$dr_cr['cr_total']),2)}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <th colspan="2">Other Expenses</th>
                            </tr>
                            <?php
                            $indirect_expense_group_id = \FinanceHelper::get_ledger_id('INDIRECT_EXPENSES_LEDGER_GROUP');
                            $indirect_expense_groups = \App\Models\COAgroups::where('parent_id', $indirect_expense_group_id)->get();
                            $indirect_exp_total = 0;
                            ?>
                            @foreach($indirect_expense_groups as $group)
                                <tr style="font-style:italic;font-weight: 600;cursor: pointer"
                                    data-target=".ledgers{{$group->id}}" class="text-danger accordion-toggle"
                                    data-toggle="collapse">
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;{{$group->name}} <i class="fa fa-chevron-down"
                                                                                    style="font-size: 12px"></td>
                                    <?php
                                    $expenses = \TaskHelper::getDrCrByGroups($group->id, $start_date, $end_date);
                                    $indirect_exp_total += abs($expenses['dr_amount']-$expenses['cr_amount']);
                                    ?>
                                    <td>{{number_format(abs($expenses['dr_amount']-$expenses['cr_amount']),2)}}</td>
                                </tr>
                                <?php
                                $ledgers = $ledger_table->where('group_id', $group->id)->get();

                                ?>
                                @foreach($ledgers as $ledger)
                                    <?php
                                    $dr_cr = TaskHelper::getLedgerDrCr($ledger, $start_date, $end_date);

                                    ?>
                                    <tr style="cursor: pointer"
                                        class="hiddenRow accordian-body collapse ledgers{{$ledger->group->id}}">
                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;
                                            <a style="font-weight:bold;font-size:13px"
                                               href="/admin/accounts/reports/ledger_statement?ledger_id={{$ledger->id}}">
                                                {{$ledger->name}}</a></td>
                                        <td>
                                            <?php
                                            ?>
                                            {{number_format(abs($dr_cr['dr_total']-$dr_cr['cr_total']),2)}}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                            <tr class="bg-info">
                                <th>Profit Before Tax</th>
                                <?php
                                $profit_before_tax = $gross_profit + $other_income - $indirect_exp_total
                                ?>
                                <th>{{number_format($profit_before_tax,2)}}</th>
                            </tr>

                                <?php
                                $tax_amount =$profit_before_tax>0?((25 / 100) * $profit_before_tax):0;
                                ?>

                                <?php
                                $profit_after_tax = $profit_before_tax - $tax_amount;
                                ?>


                        </table>
                    </div> <!-- table-responsive -->
                </div><!-- /.box-body -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>


@endsection

<!-- Optional bottom section for modals etc... -->
@section('body_bottom')
    <link href="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.css") }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap-datetimepicker.css") }}" rel="stylesheet"
          type="text/css"/>
    <script src="{{ asset("/bower_components/admin-lte/plugins/jQueryUI/jquery-ui.min.js") }}"></script>
    <script src="{{ asset ("/bower_components/admin-lte/plugins/daterangepicker/moment.js") }}"
            type="text/javascript"></script>
    <script src="{{ asset ("/bower_components/admin-lte/bootstrap/js/bootstrap-datetimepicker.js") }}"
            type="text/javascript"></script>

    {{--    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" type="text/css" />--}}
    {{--    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-treeview/1.2.0/bootstrap-treeview.min.css" rel="stylesheet" type="text/css" />--}}
    {{--    <script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>--}}
    {{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-treeview/1.2.0/bootstrap-treeview.min.js" type="text/javascript"></script>--}}

    <script type="text/javascript">
        $(function () {
            $('.datepicker').datepicker({
                //inline: true,
                dateFormat: 'yy-mm-dd',
                sideBySide: false,
            });
        });
    </script>
    <script type="text/javascript">
        var treeData = [
            {
                text: "Income",
                nodes: [
                    {
                        text: "Child-Item-1",
                        nodes: [
                            {
                                text: "Grandchild-Item-1"
                            },
                            {
                                text: "Grandchild-Item-2"
                            }
                        ]
                    },
                    {
                        text: "Child-Item-2"
                    }
                ]
            },
            {
                text: "Parent-Item-2"
            },
            {
                text: "Parent-Item-3"
            },
            {
                text: "Parent-Item-4"
            },
            {
                text: "Parent-Item-5"
            }
        ];
        // $('#myTree').treeview({
        //     data: treeData
        // });
    </script>
    <script type="text/javascript">
        $(function () {

            const allTotal = {


                drexpenses1: 0,
                crexpenses1: 0,
                drincomes1: 0,
                crincomes1: 0,

                drexpenses0: 0,
                crexpenses0: 0,
                drincomes0: 0,
                crincomes0: 0,

            }
            $('.drexpenses1').each(function () {

                allTotal.drexpenses1 += Number($(this).text());

            });

            $('.drexpenses0').each(function () {

                allTotal.drexpenses0 += Number($(this).text());

            });


            $('.crexpenses1').each(function () {

                allTotal.crexpenses1 += Number($(this).text());

            });


            $('.crexpenses0').each(function () {

                allTotal.crexpenses0 += Number($(this).text());

            });

            $('.drincomes1').each(function () {

                allTotal.drincomes1 += Number($(this).text());

            });

            $('.drincomes0').each(function () {

                allTotal.drincomes0 += Number($(this).text());

            });

            $('.crincomes1').each(function () {

                allTotal.crincomes1 += Number($(this).text());

            });

            $('.crincomes0').each(function () {

                allTotal.crincomes0 += Number($(this).text());

            });

            var incomesTotal1 = allTotal.crincomes1 - allTotal.drincomes1;
            var incomesTotal0 = allTotal.crincomes0 - allTotal.drincomes0;
            var expensesTotal1 = allTotal.drexpenses1 - allTotal.crexpenses1;
            var expensesTotal0 = allTotal.drexpenses0 - allTotal.crexpenses0;
            if (incomesTotal1 >= 0) {
                $('.incomesTotal1').text('Cr ' + incomesTotal1.toFixed(3));
            } else {
                var incomesTotal1 = -incomesTotal1;
                $('.incomesTotal1').text('Dr ' + incomesTotal1.toFixed(3));
            }

            if (incomesTotal0 >= 0) {
                $('.incomesTotal0').text('Cr ' + incomesTotal0.toFixed(3));
            } else {
                var incomesTotal0 = -incomesTotal0;
                $('.incomesTotal0').text('Dr ' + incomesTotal0.toFixed(3));
            }

            if (expensesTotal1 >= 0) {
                $('#expensesTotal1').text('Dr ' + expensesTotal1.toFixed(3));
            } else {
                var expensesTotal1 = -expensesTotal1;
                $('#expensesTotal1').text('Cr ' + expensesTotal1.toFixed(3));
            }

            if (expensesTotal0 >= 0) {
                $('#expensesTotal0').text('Dr ' + expensesTotal0.toFixed(3));
            } else {
                var expensesTotal0 = -expensesTotal0;
                $('#expensesTotal0').text('Cr ' + expensesTotal0.toFixed(3));
            }


            // $('.incomesTotal').text( (allTotal.crincomes -  allTotal.drincomes).toFixed(3)  );

            $('.grossProfit1').text((incomesTotal1 - expensesTotal1).toFixed(3));


            $('.grossProfit0').text(((incomesTotal0 + incomesTotal1) - (expensesTotal1 - expensesTotal0)).toFixed(3));

            $('.netincometotal0').text((incomesTotal0 + incomesTotal1 - expensesTotal1))

            // console.log(allTotal)

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

@endsection
