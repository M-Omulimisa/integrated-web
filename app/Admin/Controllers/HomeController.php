<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdminRoleUser;
use App\Models\Organisations\Organisation;
use App\Models\Product;
use App\Models\Training\Training;
use App\Models\TrainingSession;
use App\Models\User;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;
use Faker\Provider\ar_JO\Company;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        Admin::js('/vendor/chartjs/dist/Chart.min.js'); 
        return $content
            ->title('M-Omulimisa')
            ->description('Hello...')
            /* ->row(Dashboard::title()) */
            ->row(function (Row $row) {
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
                            'url' => route('admin.farmers.index')
                        ])
                    );
                    $link = '<a href="' . route('admin.farmers.index') . '" class="small-box-footer text-success">More info <i class="fa fa-arrow-circle-right"></i></a>';
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
                            'url' => route('admin.farmers.index')
                        ])
                    );
                    $link = '<a href="' . route('admin.farmers.index') . '" class="small-box-footer text-success">More info <i class="fa fa-arrow-circle-right"></i></a>';
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
                            'url' => route('admin.farmers.index')
                        ])
                    );
                    $link = '<a href="' . route('admin.farmers.index') . '" class="small-box-footer text-success">More info <i class="fa fa-arrow-circle-right"></i></a>';
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
                        'detail' => TrainingSession::where([
                        ])
                            ->count(),
                    ];
                    $data[] = [
                        'title' => 'Attendance',
                        'detail' => TrainingSession::where([
                        ])
                            ->count(),
                    ];
                    $box = new Box(
                        'Trainings',
                        view('admin.widgets.widget-1', [
                            'data' => $data,
                            'url' => route('admin.farmers.index')
                        ])
                    );
                    $link = '<a href="' . route('admin.farmers.index') . '" class="small-box-footer text-success">More info <i class="fa fa-arrow-circle-right"></i></a>';
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
                            'url' => route('admin.farmers.index')
                        ])
                    );
                    $link = '<a href="' . route('admin.farmers.index') . '" class="small-box-footer text-success">More info <i class="fa fa-arrow-circle-right"></i></a>';
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
                            'url' => route('admin.farmers.index')
                        ])
                    );
                    $link = '<a href="' . route('admin.farmers.index') . '" class="small-box-footer text-success">More info <i class="fa fa-arrow-circle-right"></i></a>';
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
}
