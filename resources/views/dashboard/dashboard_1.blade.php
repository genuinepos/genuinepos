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

        .card-counter.danger {
            background: #000428;
            /* fallback for old browsers */
            background: -webkit-linear-gradient(to left, #004e92, #000428);
            /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to left, #004e92, #000428);
            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

        }

        .card-counter.success {
            background: #ad5389;
            /* fallback for old browsers */
            background: -webkit-linear-gradient(to left, #3c1053, #ad5389);
            /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to left, #3c1053, #ad5389);
            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */


        }

        .card-counter.blue {
            background: #44A08D;
            /* fallback for old browsers */
            background: -webkit-linear-gradient(to left, #093637, #44A08D);
            /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to left, #093637, #44A08D);
            /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

        }

        .card-counter.green {
            background: #44A08D;
            /* fallback for old browsers */
            background: -webkit-linear-gradient(to left, #093637, #44A08D);
            /* Chrome 10-25, Safari 5.1-6 */
            background: linear-gradient(to left, #093637, #44A08D);
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
    <style>
        .select-dropdown,
        .select-dropdown * {
            margin: 0;
            padding: 0;
            position: relative;
            box-sizing: border-box;
        }

        .select-dropdown {
            position: relative;
            /* background-color: #6b082e; */
            border-radius: 4px;
        }

        .select-dropdown select {
            border-radius: 4px;
            font-size: 14px !important;
            font-weight: normal;
            max-width: 100%;
            padding: 0px 90px 0px 15px;
            border: none;
            background-color: #6b082e;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            color: #fff;
        }

        .select-dropdown select:active,
        .select-dropdown select:focus {
            outline: none;
            box-shadow: none;
        }

        /* Arrow */
        .select-dropdown::after {
            font-family: "Font Awesome 5 Free";
            font-weight: 600;
            content: "\f063";
            position: absolute;
            font-size: 10px;
            top: 0;
            right: 0;
            padding: 0px 1em;
            cursor: pointer;
            pointer-events: none;
            -webkit-transition: .25s all ease;
            -o-transition: .25s all ease;
            transition: .25s all ease;
            color: #fff;
        }

    </style>
    <style>
        .button-group {
            display: table;
        }

        .button-group__btn {
            cursor: pointer;
            display: table-cell;
            position: relative;
        }

        .button-group__btn input[type=radio],
        .button-group__btn input[type=checkbox] {
            opacity: 0;
            position: absolute;
        }

        .button-group__label {
            background-color: #6b082e;
            border-bottom: 1px solid #fff;
            border-right: 1px solid #fff;
            border-top: 1px solid #fff;
            color: #fff;
            display: block;
            padding: 0 20px;
            text-align: center;
        }

        .button-group__btn:first-child .button-group__label {
            border-left: 1px solid #fff;
            border-radius: 5px 0 0 5px;
        }

        .button-group__btn:last-child .button-group__label {
            border-radius: 0 5px 5px 0;
        }

        input:checked+.button-group__label {
            background-color: #91264f;
            border-bottom-color: #fff;
            border-top-color: #fff;
            color: #fff;
        }

        .button-group__btn:first-child input:checked+.button-group__label {
            border-left-color: #fff;
        }

        .button-group__btn:last-child input:checked+.button-group__label {
            border-right-color: #fff;
        }

        .button-group--full-width {
            table-layout: fixed;
            width: 100%;
        }

        .button-group+.button-group {
            margin-top: 10px;
        }

        @media only screen and (max-width: 1003px) {
            select {
                width: 100% !important;
            }

            .select-dropdown,
            .button-group {
                display: block !important;
            }

            .d-flex {
                display: block !important;
            }

            .card-counter {
                display: block !important;
                height: 165px;
            }
        }

        .switch_bar {
            text-align: center;
            border: 1px solid #ccc5c5;
            line-height: 20px;
            border-radius: 5px;
            /* background: #fff; */
            padding: 5%;
            box-shadow: inset 0 0 5px#ddd;
            height: 60px;
            width: 90%;
            font-size: 25px;
        }

        .switch_bar i {
            color: #6b082e;
        }

    </style>
@endpush
@section('title', 'Home - ')
@section('content')
    <div id="dashboard">
        <div class="row">
            <div class="main__content">
                <div class="row mx-3 mt-3">
                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1">
                        <div class="switch_bar">
                            <a href="" class="bar-link">
                                <span>
                                    <i class="fas fa-chart-line	"></i>
                                </span>
                                <p>

                                </p>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1">
                        <div class="switch_bar">
                            <a href="" class="bar-link">
                                <span>
                                    <i class="fa fa-group"></i>
                                </span>
                                <p>

                                </p>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1">
                        <div class="switch_bar">
                            <a href="" class="bar-link">
                                <span>
                                    <i class="fas fa-receipt"></i>
                                </span>
                                <p>

                                </p>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1">
                        <div class="switch_bar">
                            <a href="" class="bar-link">
                                <span>
                                    <i class="fas fa-home"></i>
                                </span>
                                <p>

                                </p>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1">
                        <div class="switch_bar">
                            <a href="" class="bar-link">
                                <span>
                                    <i class="fas fa-file-invoice"></i>
                                </span>
                                <p>

                                </p>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1">
                        <div class="switch_bar">
                            <a href="" class="bar-link">
                                <span>
                                    <i class="fas fa-chart-pie"></i>
                                </span>
                                <p>

                                </p>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1">
                        <div class="switch_bar">
                            <a href="" class="bar-link">
                                <span>
                                    <i class="fas fa-chart-line	"></i>
                                </span>
                                <p>

                                </p>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1">
                        <div class="switch_bar">
                            <a href="" class="bar-link">
                                <span>
                                    <i class="fa fa-group"></i>
                                </span>
                                <p>

                                </p>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1">
                        <div class="switch_bar">
                            <a href="" class="bar-link">
                                <span>
                                    <i class="fas fa-receipt"></i>
                                </span>
                                <p>

                                </p>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1">
                        <div class="switch_bar">
                            <a href="" class="bar-link">
                                <span>
                                    <i class="fas fa-home"></i>
                                </span>
                                <p>

                                </p>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1">
                        <div class="switch_bar">
                            <a href="" class="bar-link">
                                <span>
                                    <i class="fas fa-file-invoice"></i>
                                </span>
                                <p>

                                </p>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-1 col-md-2 col-sm-2 col-4 p-1">
                        <div class="switch_bar">
                            <a href="" class="bar-link">
                                <span>
                                    <i class="fas fa-chart-pie"></i>
                                </span>
                                <p>

                                </p>
                            </a>
                        </div>
                    </div>

                </div>
                {{-- <div class="sec-name pt-2 pb-1 d-flex justify-content-between align-items-center">
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


                    <div></div>
                </div> --}}

                <div class="">
                    {{-- Select Location and Filter Buttons --}}
                    <div class="row mx-2 mt-3">
                        <div class="d-flex justify-content-between align-items-center">

                            <div class="select-dropdown">
                                <select>
                                    <option value="">Select Location</option>

                                    <option value="Option 1">Custom Shop Name 1</option>
                                    <option value="Option 1">Custom Shop Name 2</option>
                                    <option value="Option 1">Custom Shop Name 3</option>
                                    <option value="Option 1">Custom Shop Name 4</option>
                                    <option value="Option 1">Custom Shop Name 5</option>
                                    <option value="Option 1">Custom Shop Name 6</option>
                                    <option value="Option 1">Custom Shop Name 7</option>

                                </select>
                            </div>

                            <div class="button-group">
                                <label class="button-group__btn">
                                    <input type="radio" name="group" />
                                    <span class="button-group__label">
                                        Today
                                    </span>
                                </label>
                                <label class="button-group__btn">
                                    <input type="radio" name="group" />
                                    <span class="button-group__label">
                                        This Week
                                    </span>
                                </label>
                                <label class="button-group__btn">
                                    <input type="radio" name="group" />
                                    <span class="button-group__label">
                                        This Month
                                    </span>
                                </label>
                                <label class="button-group__btn">
                                    <input type="radio" name="group" />
                                    <span class="button-group__label">
                                        This Financial Year
                                    </span>
                                </label>

                            </div>
                        </div>
                    </div>

                    {{-- Cards --}}
                    <div class="mx-3 mt-2">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card-counter primary d-flex justify-content-around align-content-center">
                                    <div class="icon">
                                        <i class="fas fa-receipt"></i>
                                    </div>
                                    <div class="numbers px-1">
                                        <h3 class="sub-title">Total Purchase</h3>
                                        <h1 class="title">10,324</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card-counter success d-flex justify-content-around align-content-center">
                                    <div class="icon">
                                        <i class="fas fa-money-check"></i>
                                    </div>
                                    <div class="numbers px-1">
                                        <h3 class="sub-title">Total Sale</h3>
                                        <h1 class="title">11,4324</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card-counter info d-flex justify-content-around align-content-center">
                                    <div class="icon">
                                        <i class="fas fa-clipboard"></i>
                                    </div>
                                    <div class="numbers px-1">
                                        <h3 class="sub-title">Purchase Due</h3>
                                        <h1 class="title">4324</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card-counter danger d-flex justify-content-around align-content-center">
                                    <div class="icon">
                                        <i class="fas fa-file-invoice"></i>
                                    </div>
                                    <div class="numbers px-1">
                                        <h3 class="sub-title">Invoice Due</h3>
                                        <h1 class="title">9234</h1>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card-counter info d-flex justify-content-around align-content-center">
                                    <div class="icon">
                                        <i class="fas fa-file-invoice-dollar"></i>
                                    </div>
                                    <div class="numbers px-1">
                                        <h3 class="sub-title">Expense</h3>
                                        <h1 class="title">13,3224</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card-counter danger d-flex justify-content-around align-content-center">
                                    <div class="icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="numbers px-1">
                                        <h3 class="sub-title">Total User</h3>
                                        <h1 class="title">250</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card-counter blue d-flex justify-content-around align-content-center">
                                    <div class="icon">
                                        <i class="fas fa-list"></i>
                                    </div>
                                    <div class="numbers px-1">
                                        <h3 class="sub-title">Total Products</h3>
                                        <h1 class="title">150</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card-counter success d-flex justify-content-around align-content-center">
                                    <div class="icon">
                                        <i class="fas fa-balance-scale"></i>
                                    </div>
                                    <div class="numbers px-1">
                                        <h3 class="sub-title">Total Adjustment</h3>
                                        <h1 class="title">2580
                                        </h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row px-5">
            <table id="table_id" class="display">
                <thead>
                    <tr>
                        <th>Column 1</th>
                        <th>Column 2</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Row 1 Data 1</td>
                        <td>Row 1 Data 2</td>
                    </tr>
                    <tr>
                        <td>Row 2 Data 1</td>
                        <td>Row 2 Data 2</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    </div>

@endsection


@push('scripts')
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script>
        $(document).ready(function() {
            $('#table_id').DataTable({});
        });

    </script>
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
