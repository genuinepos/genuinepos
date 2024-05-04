<style>
    td.aiability_area td {
        font-size: 11px;
        padding: 0px;
        margin: 0px !important;
        line-height: 1.5;
        height: 20px;
    }
</style>
<table class="table modal-table table-sm table-bordered">
    <tbody>
        <tr>
            <td class="aiability_area">
                <table class="table table-sm selectable">
                    <tbody>
                        {{-- Assets --}}
                        @include('accounting.reports.financial_report.ajax_view.partials.assets')
                        {{-- Assets End --}}

                        {{-- Liabilities --}}
                        @include('accounting.reports.financial_report.ajax_view.partials.liabilities')
                        {{-- Liabilities End --}}

                        {{-- Expenses --}}
                        @include('accounting.reports.financial_report.ajax_view.partials.expenses')
                        {{-- Expenses End --}}

                        {{-- Expenses --}}
                        @include('accounting.reports.financial_report.ajax_view.partials.incomes')
                        {{-- Expenses End --}}

                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
