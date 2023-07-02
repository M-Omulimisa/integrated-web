@inject('request', 'Illuminate\Http\Request')
@extends('layouts.app')

@php ($module = $course->code.': '.$course->title)

@section('content')

<!-- start page title -->
@include('partials.breadcrumb',[
    'page_title'    => '...',
    'menu_group'    => '...',
    'menu_item'     => 'E-Learning',
    'menu_item_url' => '#',
    'current'       => '...'
])
<!-- end page title -->

<!-- Row -->
<div class="row">
    <div class="col-12 col-sm-12 col-md-3">
        <div class="card">
            <div class="card-body">
                @include('e_learning.courses.menu')
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-12 col-md-9">
        <div class="card" id="pages">
            <div class="card-body">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/analytics/'.$course->id.'/overview') }}">Overview</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Student Engagement</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/analytics/'.$course->id.'/lectures') }}">Lecture Activity</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/analytics/'.$course->id.'/questions') }}">Questions/Discussions</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/analytics/'.$course->id.'/quiz') }}">Quiz Activity</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="reports">

                        {{-- <div class="row">

                            <div class="col-xl-4 col-lg-3 col-sm-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <span>Total</span>
                                        <h2 class="mb-2 mt-1 number-font">0</h2>
                                    </div> 
                                </div> 
                            </div> 

                            <div class="col-xl-4 col-lg-3 col-sm-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <span>Active</span>
                                        <h2 class="mb-2 mt-1 number-font">0</h2>
                                    </div> 
                                </div> 
                            </div> 

                            <div class="col-xl-4 col-lg-3 col-sm-4">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <span>Inactive</span>
                                        <h2 class="mb-2 mt-1 number-font">0</h2>
                                    </div> 
                                </div> 
                            </div>   

                        </div> --}}

                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-sm-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <span>Daily Enrollment</span>
                                        <div class="chartdiv" id="chart1"></div>
                                    </div> 
                                </div> 
                            </div>  
                        </div>

                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-sm-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <span>Activeness</span>
                                        <div class="chartdiv" id="activeness"></div>
                                    </div> 
                                </div> 
                            </div> 
                        </div>

                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-sm-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <span>Gender</span>
                                        <div class="chartdiv" id="gender"></div>
                                    </div> 
                                </div> 
                            </div> 
                        </div>

                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-sm-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <span>Age Group</span>
                                        <div class="chartdiv" id="age"></div>
                                    </div> 
                                </div> 
                            </div>  
                        </div>

                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-sm-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <span>Qualification</span>
                                        <div class="chartdiv" id="qualification"></div>
                                    </div> 
                                </div> 
                            </div>  
                        </div>

                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-sm-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <span>Affiliation</span>
                                        <div class="chartdiv" id="affiliation"></div>
                                    </div> 
                                </div> 
                            </div>  
                        </div>
                        

                    </div><!-- end tab-content -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Row -->
   
@endsection

@section('styles')
    
    <style type="text/css">
        .chartdiv {
              width: 100%;
              height: 500px;
            }
    </style>

@endsection

@section('scripts')

    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

    <script type="text/javascript">
        /**
         * --------------------------------------------------------
         * This demo was created using amCharts V4 preview release.
         * 
         * V4 is the latest installement in amCharts data viz
         * library family, to be released in the first half of
         * 2018.
         *
         * For more information and documentation visit:
         * https://www.amcharts.com/docs/v4/
         * --------------------------------------------------------
         */

        // Use themes
        am4core.useTheme(am4themes_animated);

        am4core.addLicense("ch-custom-attribution");

        // Create chart instance
        var chart1 = am4core.create("chart1", am4charts.XYChart);
        chart1.paddingRight = 20;

        // Add data
        chart1.data = [
        @foreach ($daily_enrollment as $enrollment)
            {
              "date": '{{ date("M j",strtotime($enrollment->day)) }}',
              "value": {{ $enrollment->studentcount }}
            },
        @endforeach
         ];

        // Create axes
        var categoryAxis = chart1.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "date";
        categoryAxis.renderer.minGridDistance = 50;
        categoryAxis.renderer.grid.template.location = 0.5;
        categoryAxis.startLocation = 0.5;
        categoryAxis.endLocation = 0.5;
        categoryAxis.renderer.labels.template.fontSize = 12;

        // Pre zoom
        chart1.events.on("datavalidated", function () {
          categoryAxis.zoomToIndexes(Math.round(chart1.data.length * 0.4), Math.round(chart1.data.length * 0.55));
        });

        // Create value axis
        var valueAxis = chart1.yAxes.push(new am4charts.ValueAxis());
        valueAxis.baseValue = 0;
        valueAxis.title.text = "Students";
        valueAxis.title.fontWeight = 800;

        // Create series
        var series = chart1.series.push(new am4charts.LineSeries());
        series.dataFields.valueY = "value";
        series.dataFields.categoryX = "date";
        series.strokeWidth = 3;
        series.tensionX = 0.8;

        var bullet = series.bullets.push(new am4charts.CircleBullet());
        bullet.strokeWidth = 0;

        bullet.adapter.add("fill", function(fill, target) {
          var values = target.dataItem.values;
          
          return values.valueY.value >= 0
            ? am4core.color("red")
            : fill;
        });

        var range = valueAxis.createSeriesRange(series);
        range.value = 0;
        range.endValue = 1000;
        range.contents.stroke = am4core.color("#FF0000");
        range.contents.fill = range.contents.stroke;

        // Add scrollbar
        var scrollbarX = new am4charts.XYChartScrollbar();
        scrollbarX.series.push(series);
        chart1.scrollbarX = scrollbarX;
    </script>

    <script type="text/javascript">
        /**
         * ---------------------------------------
         * This demo was created using amCharts 4.
         *
         * For more information visit:
         * https://www.amcharts.com/
         *
         * Documentation is available at:
         * https://www.amcharts.com/docs/v4/
         * ---------------------------------------
         */

        // Create chart instance
        var chart2 = am4core.create("gender", am4charts.PieChart);

        // Add data
        chart2.data = [
            {
              "grouping": "Male",
              "students": {{ $gender->Male }}
            }, {
              "grouping": "Female",
              "students": {{ $gender->Female }}
            }, {
              "grouping": "Not Disclosed",
              "students": {{ $gender->NotDisclosed }}
            },
        ];

        // Add and configure Series
        var pieSeries = chart2.series.push(new am4charts.PieSeries());
        pieSeries.dataFields.value = "students";
        pieSeries.dataFields.category = "grouping";
        chart2.legend = new am4charts.Legend();
    </script>


    <script type="text/javascript">
        /**
         * ---------------------------------------
         * This demo was created using amCharts 4.
         *
         * For more information visit:
         * https://www.amcharts.com/
         *
         * Documentation is available at:
         * https://www.amcharts.com/docs/v4/
         * ---------------------------------------
         */

        // Create chart instance
        var chart3 = am4core.create("age", am4charts.PieChart);

        // Add data
        chart3.data = [{
          "grouping": "None",
          "students": {{ $age->None }}
        }, {
          "grouping": "Less than 16",
          "students": {{ $age->LessThan16 }}
        }, {
          "grouping": "16-20",
          "students": {{ $age->age1620 }}
        }, {
          "grouping": "21-25",
          "students": {{ $age->age2125 }}
        }, {
          "grouping": "26-30",
          "students": {{ $age->age2630 }}
        }, {
          "grouping": "31-35",
          "students": {{ $age->age3135 }}
        }, {
          "grouping": "36-40",
          "students": {{ $age->age3640 }}
        }, {
          "grouping": "41-45",
          "students": {{ $age->age4145 }}
        }, {
          "grouping": "46-50",
          "students": {{ $age->age4650 }}
        }, {
          "grouping": "Greater than 50",
          "students": {{ $age->ageGreaterThan50 }}
        }, {
          "grouping": "Not Disclosed",
          "students": {{ $age->ageNotDisclosed }}
        }];

        // Add and configure Series
        var pieSeries = chart3.series.push(new am4charts.PieSeries());
        pieSeries.dataFields.value = "students";
        pieSeries.dataFields.category = "grouping";
        chart3.legend = new am4charts.Legend();
    </script>

    <script type="text/javascript">
        /**
         * ---------------------------------------
         * This demo was created using amCharts 4.
         *
         * For more information visit:
         * https://www.amcharts.com/
         *
         * Documentation is available at:
         * https://www.amcharts.com/docs/v4/
         * ---------------------------------------
         */

        // Create chart instance
        var chart4 = am4core.create("qualification", am4charts.PieChart);

        // Add data
        chart4.data = [{
            "grouping": "None",
              "students": {{ $qualification->None }}
            }, {
              "grouping": "Hi School",
              "students": {{ $qualification->HiSchool }}
            }, {
              "grouping": "Pre University",
              "students": {{ $qualification->PreUniversity }}
            }, {
              "grouping": "Under Graduate",
              "students": {{ $qualification->UnderGraduate }}
            }, {
              "grouping": "Post Graduate",
              "students": {{ $qualification->PostGraduate }}
            }, {
              "grouping": "Doctorate",
              "students": {{ $qualification->Doctorate }}
            }, {
              "grouping": "Other",
              "students": {{ $qualification->Other }}
            }, {
              "grouping": "Not Disclosed",
              "students": {{ $qualification->NotDisclosed }}
            }
        ];

        // Add and configure Series
        var pieSeries = chart4.series.push(new am4charts.PieSeries());
        pieSeries.dataFields.value = "students";
        pieSeries.dataFields.category = "grouping";
        chart4.legend = new am4charts.Legend();
    </script>

    <script type="text/javascript">
        /**
         * ---------------------------------------
         * This demo was created using amCharts 4.
         *
         * For more information visit:
         * https://www.amcharts.com/
         *
         * Documentation is available at:
         * https://www.amcharts.com/docs/v4/
         * ---------------------------------------
         */

        // Create chart instance
        var chart5 = am4core.create("affiliation", am4charts.PieChart);

        // Add data
        chart5.data = [{
          "grouping": "None",
              "students": {{ $affiliation->None }}
            }, {
              "grouping": "Academia",
              "students": {{ $affiliation->Academia }}
            }, {
              "grouping": "Individual",
              "students": {{ $affiliation->Individual }}
            }, {
              "grouping": "Community Organisation",
              "students": {{ $affiliation->CommunityOrganisation }}
            }, {
              "grouping": "For-Profit Organisation",
              "students": {{ $affiliation->ForProfitOrganisation }}
            }, {
              "grouping": "Non-Profit Organisation",
              "students": {{ $affiliation->NonProfitOrganisation }}
            }, {
              "grouping": "Not Disclosed",
              "students": {{ $affiliation->NotDisclosed }}
        }];

        // Add and configure Series
        var pieSeries = chart5.series.push(new am4charts.PieSeries());
        pieSeries.dataFields.value = "students";
        pieSeries.dataFields.category = "grouping";
        chart5.legend = new am4charts.Legend();
    </script>

    <script type="text/javascript">
        /**
         * ---------------------------------------
         * This demo was created using amCharts 4.
         *
         * For more information visit:
         * https://www.amcharts.com/
         *
         * Documentation is available at:
         * https://www.amcharts.com/docs/v4/
         * ---------------------------------------
         */

        // Create chart instance
        var chart6 = am4core.create("activeness", am4charts.PieChart);

        // Add data
        chart6.data = [
            {
              "grouping": "Active",
              "students": {{ $activeness->Active }}
            }, {
              "grouping": "Inactive",
              "students": {{ $activeness->Inactive }}
            },
        ];

        // Add and configure Series
        var pieSeries = chart6.series.push(new am4charts.PieSeries());
        pieSeries.dataFields.value = "students";
        pieSeries.dataFields.category = "grouping";
        chart6.legend = new am4charts.Legend();
    </script>

@endsection


