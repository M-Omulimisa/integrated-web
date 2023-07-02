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
                    <li class="nav-item"><a class="nav-link active" href="{{ url()->current() }}">Lecture Activity</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/analytics/'.$course->id.'/questions') }}">Questions/Discussions</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('e-learning/courses/analytics/'.$course->id.'/quiz') }}">Quiz Activity</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="reports">

                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div>
                                            <h3 class="card-title">Total visits per Lecture</h3>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chartdiv" id="visitsperlecture"></div>
                                    </div> 
                                </div> 
                            </div>  
                        </div>

                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div>
                                            <h3 class="card-title">Student visits per Lecture</h3>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chartdiv" id="studentvisitsperlecture"></div>
                                    </div> 
                                </div> 
                            </div>  
                        </div>

                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div>
                                            <h3 class="card-title">Lecture visits with reputation/distinction</h3>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chartdiv" id="distinctrepetitvevisits"></div>
                                    </div> 
                                </div> 
                            </div>  
                        </div>

                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div>
                                            <h3 class="card-title">Lecture attendance rate</h3>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="chartdiv" id="lectcompletionrate"></div>
                                    </div> 
                                </div> 
                            </div>  
                        </div>

                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-sm-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div>
                                            <h3 class="card-title">Student attendance</h3>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="dTable" class="table table-striped table-bordered" >
                                                <thead>
                                                    <tr>
                                                        <th>Student</th>
                                                        <th>Contact</th>
                                                        <th class="text-left">Lectures</th>
                                                        {{-- <th>Actions</th> --}}
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
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
        .content_wrapper #reports .card {
            overflow-y: hidden !important;
            overflow: auto !important;
        }
        .content_wrapper #reports .card-body {
            width: 2000px !important;
        }
        .chartdiv {
              width: 100%;
              height: 500px;
            }
        .table td:nth-child(3) {
            text-align: left !important;
        }
    </style>  

    <!--datatable css-->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.3/css/buttons.dataTables.min.css">

@endsection

@section('scripts')

    <script src="https://cdn.amcharts.com/lib/4/core.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
    <script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

    <!--datatable js-->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>    
    <script src="https://cdn.datatables.net/buttons/2.3.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.2/js/buttons.html5.min.js"></script>


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

    let chart1 = am4core.create("visitsperlecture", am4charts.XYChart);

    chart1.data = [
    @foreach ($courselectures as $lecture)
        {
          "lecture": "{{ $lecture->chapter->title }} {{ $lecture->title }}",
          "value": {{ $lecture->visitsperlecture() }}
        },
    @endforeach
    ];

    let categoryAxis = chart1.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.renderer.grid.template.location = 0;
    categoryAxis.dataFields.category = "lecture";
    categoryAxis.renderer.minGridDistance = 60;
    categoryAxis.renderer.labels.template.fontSize = 12;
    categoryAxis.renderer.labels.template.rotation = 90;
    categoryAxis.renderer.labels.template.horizontalCenter = "left";
    categoryAxis.renderer.labels.template.verticalCenter = "middle";

    var valueAxis = chart1.yAxes.push(new am4charts.ValueAxis());
    valueAxis.title.text = "Visits";
    valueAxis.title.fontWeight = 800;

    let series = chart1.series.push(new am4charts.ColumnSeries());
    series.dataFields.categoryX = "lecture";
    series.dataFields.valueY = "value";
    series.columns.template.strokeWidth = 0;
    series.tooltipText = "{valueY.value}";

    chart1.cursor = new am4charts.XYCursor();

    // Add distinctive colors for each column using adapter
    series.columns.template.adapter.add("fill", (fill, target) => {
      return chart1.colors.getIndex(target.dataItem.index);
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

    let chart2 = am4core.create("studentvisitsperlecture", am4charts.XYChart);

    chart2.data = [
    @foreach ($courselectures as $lecture)
        {
          "lecture": "{{ $lecture->chapter->title }} {{ $lecture->title }}",
          "value": {{ $lecture->studentsVisitedLecture() }}
        },
    @endforeach
    ];

    let categoryAxis2 = chart2.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis2.renderer.grid.template.location = 0;
    categoryAxis2.dataFields.category = "lecture";
    categoryAxis2.renderer.minGridDistance = 60;
    categoryAxis2.renderer.labels.template.fontSize = 12;
    categoryAxis2.renderer.labels.template.rotation = 90;
    categoryAxis2.renderer.labels.template.horizontalCenter = "left";
    categoryAxis2.renderer.labels.template.verticalCenter = "middle";

    let valueAxis2 = chart2.yAxes.push(new am4charts.ValueAxis());
    valueAxis2.title.text = "Students";
    valueAxis2.title.fontWeight = 800;

    let series2 = chart2.series.push(new am4charts.ColumnSeries());
    series2.dataFields.categoryX = "lecture";
    series2.dataFields.valueY = "value";
    series2.columns.template.strokeWidth = 0;
    series2.tooltipText = "{valueY.value}";

    chart2.cursor = new am4charts.XYCursor();

    // Add distinctive colors for each column using adapter
    series2.columns.template.adapter.add("fill", (fill, target) => {
      return chart2.colors.getIndex(target.dataItem.index);
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

    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end

    // Create chart instance
    var chart3 = am4core.create("distinctrepetitvevisits", am4charts.XYChart);


    // Add data
    chart3.data = [
    @foreach ($courselectures as $lecture)
        {
          "lecture": "{{ $lecture->chapter->title }} {{ $lecture->title }}",
          "repeat": {{ $lecture->studentsVisitedRepetitvely() }},
          "distinct": {{ $lecture->studentsVisitedDistinctly() }},
        },
    @endforeach
    ];

    // Create axes
    var categoryAxis1 = chart3.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis1.dataFields.category = "lecture";
    categoryAxis1.renderer.grid.template.location = 0;
    categoryAxis1.renderer.minGridDistance = 60;
    categoryAxis1.renderer.labels.template.fontSize = 12;
    categoryAxis1.renderer.labels.template.rotation = 90;
    categoryAxis1.renderer.labels.template.horizontalCenter = "left";
    categoryAxis1.renderer.labels.template.verticalCenter = "middle";


    var valueAxis = chart3.yAxes.push(new am4charts.ValueAxis());
    valueAxis.renderer.inside = true;
    valueAxis.renderer.labels.template.disabled = true;
    valueAxis.min = 0;
    valueAxis.title.text = "Students";
    valueAxis.title.fontWeight = 800;

    // Create series
    function createSeries(field, name) {
      
      // Set up series
      var series = chart3.series.push(new am4charts.ColumnSeries());
      series.name = name;
      series.dataFields.valueY = field;
      series.dataFields.categoryX = "lecture";
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

    createSeries("repeat", "Repeated");
    createSeries("distinct", "Distinct");

    // Legend
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

    am4core.useTheme(am4themes_animated);

    var chart4 = am4core.create("lectcompletionrate", am4charts.XYChart);
    chart4.hiddenState.properties.opacity = 0; // this creates initial fade-in

    chart4.data = [
    @foreach ($courselectures as $lecture)
      {
        category: "{{ $lecture->chapter->title }} {{ $lecture->title }}",
        completed: {{ $lecture->studentsVisitedLecture() }},
        pending: {{ count($course->enrolledStudents()) - $lecture->studentsVisitedLecture() }},
      },
    @endforeach
    ];

    chart4.colors.step = 2;
    chart4.padding(30, 30, 10, 30);
    chart4.legend = new am4charts.Legend();

    var categoryAxis3 = chart4.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis3.dataFields.category = "category";
    categoryAxis3.renderer.grid.template.location = 0;
    categoryAxis3.renderer.minGridDistance = 60;
    categoryAxis3.renderer.labels.template.fontSize = 12;
    categoryAxis3.renderer.labels.template.rotation = 90;
    categoryAxis3.renderer.labels.template.horizontalCenter = "left";
    categoryAxis3.renderer.labels.template.verticalCenter = "middle";

    var valueAxis = chart4.yAxes.push(new am4charts.ValueAxis());
    valueAxis.min = 0;
    valueAxis.max = 100;
    valueAxis.strictMinMax = true;
    valueAxis.calculateTotals = true;
    valueAxis.renderer.minWidth = 50;
    valueAxis.title.text = "Students";
    valueAxis.title.fontWeight = 800;

    var series1 = chart4.series.push(new am4charts.ColumnSeries());
    series1.columns.template.width = am4core.percent(80);
    series1.columns.template.tooltipText =
      "{name}: {valueY.totalPercent.formatNumber('#.00')}%";
    series1.name = "Visited";
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
    series3.name = "Not Visited";
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

    <script>
        $(document).ready(function() {
            var oTable = $('#dTable').DataTable({
                "sDom": "<'row'<'col-sm-3'l><'col-sm-3'i><'col-sm-6'f>r>t<'row'<'col-sm-5'i><'col-sm-7'p>>",
                "sScrollX": "100%",
                "sScrollXInner": '100%',
                "bScrollCollapse": true,
                "bProcessing": true,
                "bServerSide": true,
                "aoColumnDefs": [ {
                      // "aTargets": [0],
                      // "orderable": false,
                      // "searchable": false
                      //   "bSortable": false, 
                        
                    } ],
                ajax: {
                    url: '{!! route('e-learning.student-attendance.list') !!}',
                    data: function (d) {
                            d.course = '{{ $course->id }}';
                         }
                },
                columns: [
                    { data: 'student', name: 'student' },
                    { data: 'contact', name: 'contact' },
                    { data: 'lectures', name: 'lectures' },
                    // { data: 'actions', name: 'actions' }
                ],
                "lengthMenu": {{ DT_LENGTH }},
                "order": [[ 1, 'asc' ]],  
                "aButtons":    [ "csv", "pdf" ]
            });

                var typingTimeout = null;
                  $(".dataTables_filter input").on("keyup", function (event) {          
                    // Clear previous timer
                    clearTimeout(typingTimeout);
                    // Set a new timer
                    var that = this;
                    typingTimeout = setTimeout(function(){
                        oTable.search($(that).val()).draw();
                    }, 200); // Execute the search if user paused for 200 ms
                  });

        });
    </script>

@endsection