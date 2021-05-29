@extends('layout.master')

@push('stylesheets')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />

    <style>
        .title {
            font-size: 1.8em;
        }

        .sub-title {
            text-transform: uppercase;
            font-size: 1em;
            color: rgb(216, 204, 204)218, 199, 199);
        }

        .card-counter {
            box-shadow: 2px 2px 10px #DADADA;
            margin: 5px;
            padding: 20px 10px;
            background-color: #fff;
            color: white;
            height: 100px;
            border-radius: 5px;
            transition: .3s linear all;
        }

        .card-counter:hover {
            box-shadow: 4px 4px 20px #DADADA;
            transition: .3s linear all;
        }

        .card-counter.primary {
            background: #4e54c8;
            /* fallback for old browsers */
            background: -webkit-linear-gradient(to right, #8f94fb, #4e54c8);
            /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to right, #8f94fb, #4e54c8);
            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */


            color: #FFF;
        }

        .card-counter.success {
            background: #DCE35B;
            /* fallback for old browsers */
            background: -webkit-linear-gradient(to right, #45B649, #DCE35B);
            /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to right, #45B649, #DCE35B);
            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */


        }

        .card-counter.info {
            background: #56CCF2;
            /* fallback for old browsers */
            background: -webkit-linear-gradient(to right, #2F80ED, #56CCF2);
            /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to right, #2F80ED, #56CCF2);
            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

        }

        .card-counter.danger {
            background: #B24592;
            /* fallback for old browsers */
            background: -webkit-linear-gradient(to right, #F15F79, #B24592);
            /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to right, #F15F79, #B24592);
            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */


        }

        .card-counter i {
            font-size: 3.5em;
            padding: 10px;
            opacity: 0.5;
            color: white !important;
        }

        .card-counter .count-numbers {
            position: absolute;
            right: 35px;
            top: 20px;
            font-size: 32px;
            display: block;
        }

        .card-counter .count-name {
            position: absolute;
            right: 35px;
            top: 65px;
            font-style: italic;
            text-transform: capitalize;
            opacity: 0.5;
            display: block;
            font-size: 18px;
        }

        .shortcut-icons {
            font-size: 1.7em;
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
                        <span class="shortcut-icons p-2">
                            <i class="fas fa-home"></i>
                        </span>
                        <span class="shortcut-icons p-2">
                            <i class="fas fa-calculator"></i>
                        </span>
                        <span class="shortcut-icons p-2">
                            <i class="fas fa-user"></i>
                        </span>
                    </div>
                    <div>
                        {{-- Right side menus --}}
                    </div>
                </div>
                <div class="container-fluid">
                    {{-- <div class="row mt-4">
                        <div id="chart1" style="width:100%; height:350px;"></div>
                    </div> --}}

                    {{-- Card --}}

                    <div class="row mt-2">
                        <div class="col-md-3">
                            <div class="card-counter primary d-flex justify-content-around align-content-center">
                                <div class="icon">
                                    <i class="fas fa-database"></i>
                                </div>
                                <div class="numbers px-1">
                                    <h3 class="sub-title">Total Cost</h3>
                                    <h1 class="title">10,324324</h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card-counter success d-flex justify-content-around align-content-center">
                                <div class="icon">
                                    <i class="fas fa-database"></i>
                                </div>
                                <div class="numbers px-1">
                                    <h3 class="sub-title">Total Cost</h3>
                                    <h1 class="title">10,324324</h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card-counter info d-flex justify-content-around align-content-center">
                                <div class="icon">
                                    <i class="fas fa-database"></i>
                                </div>
                                <div class="numbers px-1">
                                    <h3 class="sub-title">Total Cost</h3>
                                    <h1 class="title">10,324324</h1>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card-counter danger d-flex justify-content-around align-content-center">
                                <div class="icon">
                                    <i class="fas fa-database"></i>
                                </div>
                                <div class="numbers px-1">
                                    <h3 class="sub-title">Total Cost</h3>
                                    <h1 class="title">10,324324</h1>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- <div class="row row-custom">
                        <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
                            <div class="info-box info-box-new-style">
                                <span class="info-box-icon bg-aqua">
                                    <i class="fas fa-user"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Total purchase</span>
                                    <span class="info-box-number total_purchase">$ 235,656.00</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
                            <div class="info-box info-box-new-style">
                                <span class="info-box-icon bg-aqua">
                                    <i class="fas fa-search-dollar"></i></span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Total Sales</span>
                                    <span class="info-box-number total_sell">$ 21,527.79</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
                            <div class="info-box info-box-new-style">
                                <span class="info-box-icon bg-yellow">
                                    <i class="fa fa-dollar"></i>
                                    <i class="fa fa-exclamation"></i>
                                </span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Purchase due</span>
                                    <span class="info-box-number purchase_due">$ 235,656.00</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->

                        <!-- fix for small devices only -->
                        <!-- <div class="clearfix visible-sm-block"></div> -->
                        <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
                            <div class="info-box info-box-new-style">
                                <span class="info-box-icon bg-yellow">
                                    <i class="ion ion-ios-paper-outline"></i>
                                    <i class="fa fa-exclamation"></i>
                                </span>

                                <div class="info-box-content">
                                    <span class="info-box-text">Invoice due</span>
                                    <span class="info-box-number invoice_due">$ -47.00</span>
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                    </div> --}}
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
