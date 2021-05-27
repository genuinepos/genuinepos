@extends('layout.master')
@push('stylesheets')
    <style>
        #dashboard {
            overflow-x: hidden;
        }

        .center {
            text-align: center;
        }

    </style>
@endpush
@section('title', 'Home - ')
@section('content')
    <div id="dashboard">
        <div class="row">
            <div class="main__content">
                <div class="sec-name pt-2 pb-1 d-flex justify-content-between align-items-center">
                    <div>
                        <span style="font-size: 1.6em;" class="p-2">
                            <i class="fas fa-home"></i>
                        </span>
                        <span style="font-size: 1.6em;" class="p-2">
                            <i class="fas fa-calculator"></i>
                        </span>
                        <span style="font-size: 1.6em;" class="p-2">
                            <i class="fas fa-user"></i>
                        </span>
                    </div>
                    <div>
                        {{-- Right side menus --}}
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="row mt-4">
                        <div id="chart1" style="width:100%; height:350px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script>
        Highcharts.chart('chart1', {
            chart: {
                type: 'area'
            },
            title: {
                text: 'Historic and Estimated Worldwide Population Growth by Region'
            },
            subtitle: {
                text: 'Source: Wikipedia.org'
            },
            xAxis: {
                categories: ['1750', '1800', '1850', '1900', '1950', '1999', '2050'],
                tickmarkPlacement: 'on',
                title: {
                    enabled: false
                }
            },
            yAxis: {
                title: {
                    text: 'Billions'
                },
                labels: {
                    formatter: function() {
                        return this.value / 1000;
                    }
                }
            },
            tooltip: {
                split: true,
                valueSuffix: ' millions'
            },
            plotOptions: {
                area: {
                    stacking: 'normal',
                    lineColor: '#666666',
                    lineWidth: 1,
                    marker: {
                        lineWidth: 1,
                        lineColor: '#666666'
                    }
                }
            },
            series: [{
                name: 'Asia',
                data: [502, 635, 809, 947, 1402, 3634, 5268]
            }, {
                name: 'Africa',
                data: [106, 107, 111, 133, 221, 767, 1766]
            }, {
                name: 'Europe',
                data: [163, 203, 276, 408, 547, 729, 628]
            }, {
                name: 'America',
                data: [18, 31, 54, 156, 339, 818, 1201]
            }, {
                name: 'Oceania',
                data: [2, 2, 2, 6, 13, 30, 46]
            }]
        });

    </script>
@endpush
