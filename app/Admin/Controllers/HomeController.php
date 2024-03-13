<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdminRoleUser;
use App\Models\OnlineCourse;
use App\Models\Organisations\Organisation;
use App\Models\Product;
use App\Models\Training\Training;
use App\Models\TrainingSession;
use App\Models\User;
use App\Models\Utils;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;
use Faker\Provider\ar_JO\Company;

class HomeController extends Controller
{

    public function stats(Content $content)
    {
        $course = Training::where([])
            ->get();
        Admin::js('/vendor/chartjs/dist/Chart.min.js');
        return $content
            ->title(strtoupper('Online Courses - Statistics'))
            ->row(function (Row $row) {
                $row->column(3, function (Column $column) {
                    $data = [];
                    $data[] = [
                        'title' => 'Organisation',
                        'detail' => Organisation::count(),
                    ];
                    $data[] = [
                        'title' => 'Students',
                        'detail' => \App\Models\User::count(),
                    ];
                    $data[] = [
                        'title' => 'Extension Calls',
                        'detail' => AdminRoleUser::where([
                            'role_id' => 2
                        ])
                            ->count(),
                    ];
                    $box = new Box(
                        'Courses',
                        view('admin.widgets.widget-1', [
                            'data' => $data,
                            'url' => ('admin.farmers.index')
                        ])
                    );
                    $link = '<a href="' . ('admin.farmers.index') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
                    $box->style('success')
                        ->footer($link)
                        ->solid()
                        ->collapsable()
                        ->removable();
                    $column->append($box);
                });

                $row->column(3, function (Column $column) {
                    $data = [];
                    $data[] = [
                        'title' => 'All Farmers',
                        'detail' => \App\Models\Farmers\Farmer::count(),
                    ];
                    $data[] = [
                        'title' => 'Farmer Groups',
                        'detail' => \App\Models\Farmers\FarmerGroup::count(),
                    ];
                    $data[] = [
                        'title' => 'Individual farmers',
                        'detail' => \App\Models\Farmers\Farmer::where([
                            'farmer_group_id' => 1
                        ])
                            ->count(),
                    ];
                    $box = new Box(
                        'Recent Calls',
                        view('admin.widgets.widget-1', [
                            'data' => $data,
                            'url' => ('admin.farmers.index')
                        ])
                    );
                    $link = '<a href="' . ('admin.farmers.index') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
                    $box->style('success')
                        ->footer($link)
                        ->solid()
                        ->collapsable()
                        ->removable();
                    $column->append($box);
                });

                $row->column(3, function (Column $column) {
                    $data = [];
                    $data[] = [
                        'title' => 'Products',
                        'detail' => Product::count(),
                    ];
                    $data[] = [
                        'title' => 'Vendors',
                        'detail' => AdminRoleUser::where([
                            'role_id' => 3
                        ])
                            ->count(),
                    ];
                    $data[] = [
                        'title' => 'Orders',
                        'detail' => \App\Models\Farmers\Farmer::where([
                            'farmer_group_id' => 1
                        ])
                            ->count(),
                    ];
                    $box = new Box(
                        'Students',
                        view('admin.widgets.widget-1', [
                            'data' => $data,
                            'url' => ('admin.farmers.index')
                        ])
                    );
                    $link = '<a href="' . ('admin.farmers.index') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
                    $box->style('success')
                        ->footer($link)
                        ->solid()
                        ->collapsable()
                        ->removable();
                    $column->append($box);
                });

                $row->column(3, function (Column $column) {
                    $data = [];
                    $data[] = [
                        'title' => 'Courses',
                        'detail' => Training::count(),
                    ];
                    $data[] = [
                        'title' => 'Students',
                        'detail' => TrainingSession::where([])
                            ->count(),
                    ];
                    $data[] = [
                        'title' => 'Recent Calls',
                        'detail' => TrainingSession::where([])
                            ->count(),
                    ];
                    $box = new Box(
                        'Best performers',
                        view('admin.widgets.widget-1', [
                            'data' => $data,
                            'url' => ('admin.farmers.index')
                        ])
                    );
                    $link = '<a href="' . ('admin.farmers.index') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
                    $box->style('success')
                        ->footer($link)
                        ->solid()
                        ->collapsable()
                        ->removable();
                    $column->append($box);
                });


                /*                

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::extensions());
                });

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::dependencies());
                }); */
            })->row(function (Row $row) {
                $row->column(6, function (Column $column) {
                    $data = [];
                    $data[] = [
                        'title' => 'Organisation',
                        'detail' => Organisation::count(),
                    ];
                    $data[] = [
                        'title' => 'Registered Users',
                        'detail' => \App\Models\User::count(),
                    ];
                    $data[] = [
                        'title' => 'Extension Officers',
                        'detail' => AdminRoleUser::where([
                            'role_id' => 2
                        ])
                            ->count(),
                    ];
                    $box = new Box(
                        'System Users',
                        view('admin.widgets.widget-graph-1', [
                            'data' => $data,
                            'url' => ('admin.farmers.index')
                        ])
                    );
                    $link = '<a href="' . ('admin.farmers.index') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
                    $box->style('success')
                        ->footer($link)
                        ->solid()
                        ->collapsable()
                        ->removable();
                    $column->append($box);
                });

                $row->column(6, function (Column $column) {
                    $data = [];
                    $data[] = [
                        'title' => 'Organisation',
                        'detail' => Organisation::count(),
                    ];
                    $data[] = [
                        'title' => 'Registered Users',
                        'detail' => \App\Models\User::count(),
                    ];
                    $data[] = [
                        'title' => 'Extension Officers',
                        'detail' => AdminRoleUser::where([
                            'role_id' => 2
                        ])
                            ->count(),
                    ];
                    $box = new Box(
                        'System Users',
                        view('admin.widgets.widget-graph-2', [
                            'data' => $data,
                            'url' => ('admin.farmers.index')
                        ])
                    );
                    $link = '<a href="' . ('admin.farmers.index') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
                    $box->style('success')
                        ->footer($link)
                        ->solid()
                        ->collapsable()
                        ->removable();
                    $column->append($box);
                });




                /*                

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::extensions());
                });

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::dependencies());
                }); */
            });
    }

    public function index(Content $content)
    {

        $u = Admin::user();
        Admin::js('/vendor/chartjs/dist/Chart.min.js');
        $content
            ->title('M-Omulimisa')
            ->description(Utils::greet() . " " . $u->name . ".");

        if ($u->isRole('instructor')) {


            $content->row(function (Row $row) {
                $row->column(3, function (Column $column) {
                    $u = Admin::user();
                    $myCourses = OnlineCourse::getMyCouses($u);
                    $data = [];
                    $emptyLines = 3;
                    foreach ($myCourses as $key => $value) {
                        $data[] = [
                            'title' => strtoupper(substr($value->title, 0, 20)),
                            'detail' => $value->students->count(),
                        ];
                        $emptyLines--;
                    }
                    for ($i = 0; $i < $emptyLines; $i++) {
                        $data[] = [
                            'title' => '',
                            'detail' => '',
                        ];
                    }
                    $box = new Box(
                        'My Courses',
                        view('admin.widgets.widget-1', [
                            'data' => $data,
                            'url' => admin_url('e-learning-courses')
                        ])
                    );

                    $link = '<a href="' . admin_url('e-learning-courses') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
                    $box->style('success')
                        ->footer($link)
                        ->solid()
                        ->collapsable()
                        ->removable();
                    $column->append($box);
                });

                $row->column(3, function (Column $column) {
                    $u = Admin::user();
                    $data = [];
                    $myStudents = OnlineCourse::getMyStudents($u);
                    $data[] = [
                        'title' => strtoupper('Total Students'),
                        'detail' => count($myStudents),
                    ];
                    $completed = [];
                    $incomplete = [];
                    foreach ($myStudents as $key => $value) {
                        if ($value['progress'] >= 99) {
                            $completed[] = $value;
                        } else {
                            $incomplete[] = $value;
                        }
                    }

                    $data[] = [
                        'title' => strtoupper('Completed'),
                        'detail' => count($completed),
                    ];

                    $data[] = [
                        'title' => strtoupper('Incomplete'),
                        'detail' => count($incomplete),
                    ];

                    $box = new Box(
                        'My Students',
                        view('admin.widgets.widget-1', [
                            'data' => $data,
                            'url' => ('admin.farmers.index')
                        ])
                    );
                    $link = '<a href="' . ('admin.farmers.index') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
                    $box->style('success')
                        ->footer($link)
                        ->solid()
                        ->collapsable()
                        ->removable();
                    $column->append($box);
                });


                /*                
                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::extensions());
                });

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::dependencies());
                }); */
            });
        }

        if ($u->isRole('administrator')) {
            $content->row(function (Row $row) {
                $row->column(3, function (Column $column) {
                    $data = [];
                    $data[] = [
                        'title' => 'Organisation',
                        'detail' => Organisation::count(),
                    ];
                    $data[] = [
                        'title' => 'Registered Users',
                        'detail' => \App\Models\User::count(),
                    ];
                    $data[] = [
                        'title' => 'Extension Officers',
                        'detail' => AdminRoleUser::where([
                            'role_id' => 2
                        ])
                            ->count(),
                    ];
                    $box = new Box(
                        'System Users',
                        view('admin.widgets.widget-1', [
                            'data' => $data,
                            'url' => ('admin.farmers.index')
                        ])
                    );
                    $link = '<a href="' . ('admin.farmers.index') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
                    $box->style('success')
                        ->footer($link)
                        ->solid()
                        ->collapsable()
                        ->removable();
                    $column->append($box);
                });

                $row->column(3, function (Column $column) {
                    $data = [];
                    $data[] = [
                        'title' => 'All Farmers',
                        'detail' => \App\Models\Farmers\Farmer::count(),
                    ];
                    $data[] = [
                        'title' => 'Farmer Groups',
                        'detail' => \App\Models\Farmers\FarmerGroup::count(),
                    ];
                    $data[] = [
                        'title' => 'Individual farmers',
                        'detail' => \App\Models\Farmers\Farmer::where([
                            'farmer_group_id' => 1
                        ])
                            ->count(),
                    ];
                    $box = new Box(
                        'Famers registered',
                        view('admin.widgets.widget-1', [
                            'data' => $data,
                            'url' => ('admin.farmers.index')
                        ])
                    );
                    $link = '<a href="' . ('admin.farmers.index') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
                    $box->style('success')
                        ->footer($link)
                        ->solid()
                        ->collapsable()
                        ->removable();
                    $column->append($box);
                });

                $row->column(3, function (Column $column) {
                    $data = [];
                    $data[] = [
                        'title' => 'Products',
                        'detail' => Product::count(),
                    ];
                    $data[] = [
                        'title' => 'Vendors',
                        'detail' => AdminRoleUser::where([
                            'role_id' => 3
                        ])
                            ->count(),
                    ];
                    $data[] = [
                        'title' => 'Orders',
                        'detail' => \App\Models\Farmers\Farmer::where([
                            'farmer_group_id' => 1
                        ])
                            ->count(),
                    ];
                    $box = new Box(
                        'Marketplace',
                        view('admin.widgets.widget-1', [
                            'data' => $data,
                            'url' => ('admin.farmers.index')
                        ])
                    );
                    $link = '<a href="' . ('admin.farmers.index') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
                    $box->style('success')
                        ->footer($link)
                        ->solid()
                        ->collapsable()
                        ->removable();
                    $column->append($box);
                });

                $row->column(3, function (Column $column) {
                    $data = [];
                    $data[] = [
                        'title' => 'Upcoming Trainings',
                        'detail' => Training::count(),
                    ];
                    $data[] = [
                        'title' => 'Conducted Trainings',
                        'detail' => TrainingSession::where([])
                            ->count(),
                    ];
                    $data[] = [
                        'title' => 'Attendance',
                        'detail' => TrainingSession::where([])
                            ->count(),
                    ];
                    $box = new Box(
                        'Trainings',
                        view('admin.widgets.widget-1', [
                            'data' => $data,
                            'url' => ('admin.farmers.index')
                        ])
                    );
                    $link = '<a href="' . ('admin.farmers.index') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
                    $box->style('success')
                        ->footer($link)
                        ->solid()
                        ->collapsable()
                        ->removable();
                    $column->append($box);
                });


                /*                
                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::extensions());
                });

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::dependencies());
                }); */
            })->row(function (Row $row) {
                $row->column(6, function (Column $column) {
                    $data = [];
                    $data[] = [
                        'title' => 'Organisation',
                        'detail' => Organisation::count(),
                    ];
                    $data[] = [
                        'title' => 'Registered Users',
                        'detail' => \App\Models\User::count(),
                    ];
                    $data[] = [
                        'title' => 'Extension Officers',
                        'detail' => AdminRoleUser::where([
                            'role_id' => 2
                        ])
                            ->count(),
                    ];
                    $box = new Box(
                        'System Users',
                        view('admin.widgets.widget-graph-1', [
                            'data' => $data,
                            'url' => ('admin.farmers.index')
                        ])
                    );
                    $link = '<a href="' . ('admin.farmers.index') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
                    $box->style('success')
                        ->footer($link)
                        ->solid()
                        ->collapsable()
                        ->removable();
                    $column->append($box);
                });

                $row->column(6, function (Column $column) {
                    $data = [];
                    $data[] = [
                        'title' => 'Organisation',
                        'detail' => Organisation::count(),
                    ];
                    $data[] = [
                        'title' => 'Registered Users',
                        'detail' => \App\Models\User::count(),
                    ];
                    $data[] = [
                        'title' => 'Extension Officers',
                        'detail' => AdminRoleUser::where([
                            'role_id' => 2
                        ])
                            ->count(),
                    ];
                    $box = new Box(
                        'System Users',
                        view('admin.widgets.widget-graph-2', [
                            'data' => $data,
                            'url' => ('admin.farmers.index')
                        ])
                    );
                    $link = '<a href="' . ('admin.farmers.index') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
                    $box->style('success')
                        ->footer($link)
                        ->solid()
                        ->collapsable()
                        ->removable();
                    $column->append($box);
                });




                /*                

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::extensions());
                });

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::dependencies());
                }); */
            });
        }

        return $content;
    }
}
