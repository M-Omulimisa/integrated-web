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
                    <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/analytics/'.$course->id.'/students') }}">Student Engagement</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/analytics/'.$course->id.'/lectures') }}">Lecture Activity</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/analytics/'.$course->id.'/questions') }}">Questions/Discussions</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Quiz Activity</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="reports">

                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-sm-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <span>Chapter Quiz: Students attempted the Questions</span>
                                        @if (count($course->lecture_quiz_questions()) > 0)
                                            <div class="chartdiv" id="studentsattemptedquiz"></div>
                                        @else
                                         No questions
                                        @endif
                                    </div> 
                                </div> 
                            </div>  
                        </div>

                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-sm-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <span>General Quiz: Students attempted the Questions</span>
                                        @if (count($course->lecture_general_questions) > 0)
                                            <div class="chartdiv" id="studentsattemptedgeneralquiz"></div>
                                        @else
                                            No questions
                                        @endif
                                    </div> 
                                </div> 
                            </div>  
                        </div> 

                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-sm-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <span>Chapter Quiz: Student performance per Question (Passed VS Failed)</span>
                                        @if (count($course->lecture_quiz_questions()) > 0)
                                            <div class="chartdiv" id="chapterpassvsfail"></div>
                                        @else
                                         No questions
                                        @endif
                                    </div> 
                                </div> 
                            </div>  
                        </div>

                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-sm-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <span>General Quiz: Student performance per Question (Passed VS Failed)</span>
                                        @if (count($course->lecture_general_questions) > 0)
                                            <div class="chartdiv" id="generalpassvsfail"></div>
                                        @else
                                            No questions
                                        @endif
                                    </div> 
                                </div> 
                            </div>  
                        </div>  

                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-sm-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <span>Chapter Quiz: Pass/Failure Rate</span>
                                        @if (count($course->lecture_quiz_questions()) > 0)
                                            <div class="chartdiv" id="chapterpassfailurerate"></div>
                                        @else
                                         No questions
                                        @endif
                                    </div> 
                                </div> 
                            </div>  
                        </div>

                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-sm-12">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <span>General Quiz: Pass/Failure Rate</span>
                                        @if (count($course->lecture_general_questions) > 0)
                                            <div class="chartdiv" id="generalpassfailurerate"></div>
                                        @else
                                            No questions
                                        @endif
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

        // Use themes
            am4core.useTheme(am4themes_animated);

            am4core.addLicense("ch-custom-attribution");

        // Create chart instance
        var chart1 = am4core.create("chapterpassvsfail", am4charts.XYChart);


        // Add data
        chart1.data = [
        @foreach ($course->lecture_quiz_questions() as $question)
            {
              "question": "Q#{{ $question->numbering }}",
              "passed": {{ $question->studentsPassedQuestion() }},
              "failed": {{ $question->studentsFailedQuestion() }},
            },
        @endforeach
        ];

        // Create axes
        var categoryAxis = chart1.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "question";
        categoryAxis.renderer.grid.template.location = 0;
        categoryAxis.renderer.labels.template.fontSize = 12;


        var valueAxis = chart1.yAxes.push(new am4charts.ValueAxis());
        valueAxis.renderer.inside = true;
        valueAxis.renderer.labels.template.disabled = true;
        valueAxis.min = 0;
        valueAxis.title.text = "Students (Out of {{ count($course->enrolledStudents()) }} students)";
        valueAxis.title.fontWeight = 800;

        // Create series
        function createSeries(field, name) {
          
          // Set up series
          var series = chart1.series.push(new am4charts.ColumnSeries());
          series.name = name;
          series.dataFields.valueY = field;
          series.dataFields.categoryX = "question";
          series.sequencedInterpolation = true;
          
          // Make it stacked
          series.stacked = true;
          
          // Configure columns
          series.columns.template.width = am4core.percent(60);
          series.columns.template.tooltipText = "[bold]{name}[/]\n[font-size:14px]{categoryX}: {valueY}";
          
          // Add label
          var labelBullet = series.bullets.push(new am4charts.LabelBullet());
          labelBullet.label.text = "{valueY}";
          labelBullet.locationY = 0.5;
          labelBullet.label.hideOversized = true;
          
          return series;
        }

        createSeries("passed", "Passed");
        createSeries("failed", "Failed");

        // Legend
        chart1.legend = new am4charts.Legend();
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

        // Use themes
            am4core.useTheme(am4themes_animated);

            am4core.addLicense("ch-custom-attribution");

        // Create chart instance
        var chart2 = am4core.create("generalpassvsfail", am4charts.XYChart);


        // Add data
        chart2.data = [
        @foreach ($course->lecture_general_questions as $question)
            {
              "question": "Q#{{ $question->numbering }}",
              "passed": {{ $question->studentsPassedGeneralQuestion() }},
              "failed": {{ $question->studentsFailedGeneralQuestion() }},
            },
        @endforeach
        ];

        // Create axes
        var categoryAxis1 = chart2.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis1.dataFields.category = "question";
        categoryAxis1.renderer.grid.template.location = 0;
        categoryAxis1.renderer.labels.template.fontSize = 12;


        var valueAxis = chart2.yAxes.push(new am4charts.ValueAxis());
        valueAxis.renderer.inside = true;
        valueAxis.renderer.labels.template.disabled = true;
        valueAxis.min = 0;
        valueAxis.title.text = "Students (Out of {{ count($course->enrolledStudents()) }} students)";
        valueAxis.title.fontWeight = 800;

        // Create series
        function createSeries(field, name) {
          
          // Set up series
          var series = chart2.series.push(new am4charts.ColumnSeries());
          series.name = name;
          series.dataFields.valueY = field;
          series.dataFields.categoryX = "question";
          series.sequencedInterpolation = true;
          
          // Make it stacked
          series.stacked = true;
          
          // Configure columns
          series.columns.template.width = am4core.percent(60);
          series.columns.template.tooltipText = "[bold]{name}[/]\n[font-size:14px]{categoryX}: {valueY}";
          
          // Add label
          var labelBullet = series.bullets.push(new am4charts.LabelBullet());
          labelBullet.label.text = "{valueY}";
          labelBullet.locationY = 0.5;
          labelBullet.label.hideOversized = true;
          
          return series;
        }

        createSeries("passed", "Passed");
        createSeries("failed", "Failed");

        // Legend
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

        am4core.useTheme(am4themes_animated);

        var chart4 = am4core.create("chapterpassfailurerate", am4charts.XYChart);
        chart4.hiddenState.properties.opacity = 0; // this creates initial fade-in

        chart4.data = [
        @foreach ($course->lecture_quiz_questions() as $question)
          {
            category: "Q#{{ $question->numbering }}",
            completed: {{ $question->studentsPassedQuestion() }},
            pending: {{ $question->studentsFailedQuestion() }},
          },
        @endforeach
        ];

        chart4.colors.step = 2;
        chart4.padding(30, 30, 10, 30);
        chart4.legend = new am4charts.Legend();

        var categoryAxis3 = chart4.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis3.dataFields.category = "category";
        categoryAxis3.renderer.grid.template.location = 0;
        categoryAxis3.renderer.labels.template.fontSize = 12;

        var valueAxis = chart4.yAxes.push(new am4charts.ValueAxis());
        valueAxis.min = 0;
        valueAxis.max = 100;
        valueAxis.strictMinMax = true;
        valueAxis.calculateTotals = true;
        valueAxis.renderer.minWidth = 50;
        valueAxis.title.text = "Questions";
        valueAxis.title.fontWeight = 800;

        var series1 = chart4.series.push(new am4charts.ColumnSeries());
        series1.columns.template.width = am4core.percent(80);
        series1.columns.template.tooltipText =
          "{name}: {valueY.totalPercent.formatNumber('#.00')}%";
        series1.name = "Passed";
        series1.dataFields.categoryX = "category";
        series1.dataFields.valueY = "completed";
        series1.dataFields.valueYShow = "totalPercent";
        series1.dataItems.template.locations.categoryX = 0.5;
        series1.stacked = true;
        series1.tooltip.pointerOrientation = "vertical";

        var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
        bullet1.label.text = "{valueY.totalPercent.formatNumber('#.00')}%";
        bullet1.label.fill = am4core.color("#ffffff");
        bullet1.locationY = 0.5;

        var series3 = chart4.series.push(new am4charts.ColumnSeries());
        series3.columns.template.width = am4core.percent(80);
        series3.columns.template.tooltipText =
          "{name}: {valueY.totalPercent.formatNumber('#.00')}%";
        series3.name = "Failed";
        series3.dataFields.categoryX = "category";
        series3.dataFields.valueY = "pending";
        series3.dataFields.valueYShow = "totalPercent";
        series3.dataItems.template.locations.categoryX = 0.5;
        series3.stacked = true;
        series3.tooltip.pointerOrientation = "vertical";

        var bullet2 = series3.bullets.push(new am4charts.LabelBullet());
        bullet2.label.text = "{valueY.totalPercent.formatNumber('#.00')}%";
        bullet2.locationY = 0.5;
        bullet2.label.fill = am4core.color("#ffffff");

        // var series3 = chart4.series.push(new am4charts.ColumnSeries());
        // series3.columns.template.width = am4core.percent(80);
        // series3.columns.template.tooltipText =
        //   "{name}: {valueY.totalPercent.formatNumber('#.00')}%";
        // series3.name = "Series 3";
        // series3.dataFields.categoryX = "category";
        // series3.dataFields.valueY = "value3";
        // series3.dataFields.valueYShow = "totalPercent";
        // series3.dataItems.template.locations.categoryX = 0.5;
        // series3.stacked = true;
        // series3.tooltip.pointerOrientation = "vertical";

        // var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
        // bullet3.label.text = "{valueY.totalPercent.formatNumber('#.00')}%";
        // bullet3.locationY = 0.5;
        // bullet3.label.fill = am4core.color("#ffffff");

        chart4.scrollbarX = new am4core.Scrollbar();
    </script>

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

        let chart6 = am4core.create("studentsattemptedquiz", am4charts.XYChart);

        chart6.data = [
            @foreach ($course->lecture_quiz_questions() as $question)
               {
                  "questions": "Q#{{ $question->numbering }}",
                  "value": {{ count($question->studentsAttemptedQuestion()) }}
                },
            @endforeach
        ];

        let categoryAxis2 = chart6.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis2.renderer.grid.template.location = 0;
        categoryAxis2.dataFields.category = "questions";
        categoryAxis2.renderer.minGridDistance = 60;
        categoryAxis2.renderer.labels.template.fontSize = 12;

        let valueAxis2 = chart6.yAxes.push(new am4charts.ValueAxis());
        valueAxis2.title.text = "Students (Out of {{ count($course->enrolledStudents()) }} students)";
        valueAxis2.title.fontWeight = 800;

        let series2 = chart6.series.push(new am4charts.ColumnSeries());
        series2.dataFields.categoryX = "questions";
        series2.dataFields.valueY = "value";
        series2.columns.template.strokeWidth = 0;
        series2.tooltipText = "{valueY.value}";

        chart6.cursor = new am4charts.XYCursor();

        // Add distinctive colors for each column using adapter
        series2.columns.template.adapter.add("fill", (fill, target) => {
          return chart6.colors.getIndex(target.dataItem.index);
        });
    </script>

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

        let chart7 = am4core.create("studentsattemptedgeneralquiz", am4charts.XYChart);

        chart7.data = [
            @foreach ($course->lecture_general_questions as $question)
               {
                  "questions": "Q#{{ $question->numbering }}",
                  "value": {{ count($question->studentsAttemptedGeneralQuestion()) }}
                },
            @endforeach
        ];

        let categoryAxis4 = chart7.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis4.renderer.grid.template.location = 0;
        categoryAxis4.dataFields.category = "questions";
        categoryAxis4.renderer.minGridDistance = 60;
        categoryAxis4.renderer.labels.template.fontSize = 12;

        let valueAxis3 = chart7.yAxes.push(new am4charts.ValueAxis());
        valueAxis3.title.text = "Students (Out of {{ count($course->enrolledStudents()) }} students)";
        valueAxis3.title.fontWeight = 800;

        let series4 = chart7.series.push(new am4charts.ColumnSeries());
        series4.dataFields.categoryX = "questions";
        series4.dataFields.valueY = "value";
        series4.columns.template.strokeWidth = 0;
        series4.tooltipText = "{valueY.value}";

        chart7.cursor = new am4charts.XYCursor();

        // Add distinctive colors for each column using adapter
        series4.columns.template.adapter.add("fill", (fill, target) => {
          return chart7.colors.getIndex(target.dataItem.index);
        });
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

        am4core.useTheme(am4themes_animated);

        var chart8 = am4core.create("generalpassfailurerate", am4charts.XYChart);
        chart8.hiddenState.properties.opacity = 0; // this creates initial fade-in

        chart8.data = [
        @foreach ($course->lecture_general_questions as $question)
          {
            category: "Q#{{ $question->numbering }}",
            completed: {{ $question->studentsPassedGeneralQuestion() }},
            pending: {{ $question->studentsFailedGeneralQuestion() }},
          },
        @endforeach
        ];

        chart8.colors.step = 2;
        chart8.padding(30, 30, 10, 30);
        chart8.legend = new am4charts.Legend();

        var categoryAxis5 = chart8.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis5.dataFields.category = "category";
        categoryAxis5.renderer.grid.template.location = 0;
        categoryAxis5.renderer.labels.template.fontSize = 12;

        var valueAxis = chart8.yAxes.push(new am4charts.ValueAxis());
        valueAxis.min = 0;
        valueAxis.max = 100;
        valueAxis.strictMinMax = true;
        valueAxis.calculateTotals = true;
        valueAxis.renderer.minWidth = 50;
        valueAxis.title.text = "Questions";
        valueAxis.title.fontWeight = 800;

        var series1 = chart8.series.push(new am4charts.ColumnSeries());
        series1.columns.template.width = am4core.percent(80);
        series1.columns.template.tooltipText =
          "{name}: {valueY.totalPercent.formatNumber('#.00')}%";
        series1.name = "Passed";
        series1.dataFields.categoryX = "category";
        series1.dataFields.valueY = "completed";
        series1.dataFields.valueYShow = "totalPercent";
        series1.dataItems.template.locations.categoryX = 0.5;
        series1.stacked = true;
        series1.tooltip.pointerOrientation = "vertical";

        var bullet1 = series1.bullets.push(new am4charts.LabelBullet());
        bullet1.label.text = "{valueY.totalPercent.formatNumber('#.00')}%";
        bullet1.label.fill = am4core.color("#ffffff");
        bullet1.locationY = 0.5;

        var series3 = chart8.series.push(new am4charts.ColumnSeries());
        series3.columns.template.width = am4core.percent(80);
        series3.columns.template.tooltipText =
          "{name}: {valueY.totalPercent.formatNumber('#.00')}%";
        series3.name = "Failed";
        series3.dataFields.categoryX = "category";
        series3.dataFields.valueY = "pending";
        series3.dataFields.valueYShow = "totalPercent";
        series3.dataItems.template.locations.categoryX = 0.5;
        series3.stacked = true;
        series3.tooltip.pointerOrientation = "vertical";

        var bullet2 = series3.bullets.push(new am4charts.LabelBullet());
        bullet2.label.text = "{valueY.totalPercent.formatNumber('#.00')}%";
        bullet2.locationY = 0.5;
        bullet2.label.fill = am4core.color("#ffffff");

        // var series3 = chart8.series.push(new am4charts.ColumnSeries());
        // series3.columns.template.width = am4core.percent(80);
        // series3.columns.template.tooltipText =
        //   "{name}: {valueY.totalPercent.formatNumber('#.00')}%";
        // series3.name = "Series 3";
        // series3.dataFields.categoryX = "category";
        // series3.dataFields.valueY = "value3";
        // series3.dataFields.valueYShow = "totalPercent";
        // series3.dataItems.template.locations.categoryX = 0.5;
        // series3.stacked = true;
        // series3.tooltip.pointerOrientation = "vertical";

        // var bullet3 = series3.bullets.push(new am4charts.LabelBullet());
        // bullet3.label.text = "{valueY.totalPercent.formatNumber('#.00')}%";
        // bullet3.locationY = 0.5;
        // bullet3.label.fill = am4core.color("#ffffff");

        chart8.scrollbarX = new am4core.Scrollbar();
    </script>
@endsection


