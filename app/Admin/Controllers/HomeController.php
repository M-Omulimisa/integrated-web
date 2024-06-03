<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AdminRoleUser;
use App\Models\Insurance\InsuranceSubscription;
use App\Models\ItemPrice;
use App\Models\Market\Market;
use App\Models\Market\MarketPackage;
use App\Models\Market\MarketSubscription;
use App\Models\OnlineCourse;
use App\Models\Organisations\Organisation;
use App\Models\Product;
use App\Models\Settings\Enterprise;
use App\Models\Training\Training;
use App\Models\TrainingSession;
use App\Models\User;
use App\Models\Utils;
use App\Models\Weather\WeatherSubscription;
use Carbon\Carbon;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;
use Faker\Provider\ar_JO\Company;
use Illuminate\Support\Str;

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
                            'url' => ('/')
                        ])
                    );
                    $link = '<a href="' . ('/') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
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
                            'url' => ('/')
                        ])
                    );
                    $link = '<a href="' . ('/') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
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
                            'url' => ('/')
                        ])
                    );
                    $link = '<a href="' . ('/') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
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
                            'url' => ('/')
                        ])
                    );
                    $link = '<a href="' . ('/') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
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
                            'url' => ('/users')
                        ])
                    );
                    $link = '<a href="' . ('/') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
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
                            'url' => ('/')
                        ])
                    );
                    $link = '<a href="' . ('/') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
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
        if ($u != 'Yes') {
            if ($u->has_changed_password != 'Yes') {
                $token = rand(100000, 999999);
                $u->reset_password_token = $token;
                $u->save();
                //set token in session
                session(['reset_password_token' => $token]);
                Admin::script('window.location.replace("' . url('auth/password-reset-form') . '");');
                return $content;
            }
        }


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
                            'url' => ('/')
                        ])
                    );
                    $link = '<a href="' . ('/') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
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
                            'url' => ('/users')
                        ])
                    );
                    $link = '<a href="' . ('/users') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
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
                            'url' => ('/')
                        ])
                    );
                    $link = '<a href="' . ('/farmers') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
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
                            'url' => ('/products')
                        ])
                    );
                    $link = '<a href="' . ('/products') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
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
                        'title' => 'Market Subscriptions',
                        'detail' => MarketSubscription::where([
                            'status' => 1,
                            'is_paid' => 'PAID'
                        ])
                            ->count(),
                    ];
                    $data[] = [
                        'title' => 'Weather Subscriptions',
                        'detail' => WeatherSubscription::where([
                            'status' => 1,
                            'is_paid' => 'PAID'
                        ])
                            ->count(),
                    ];
                    $data[] = [
                        'title' => 'Insurance Subscriptions',
                        'detail' => InsuranceSubscription::where([
                            'status' => 1
                        ])
                            ->count(),
                    ];
                    $box = new Box(
                        'Active Subscriptions',
                        view('admin.widgets.widget-1', [
                            'data' => $data,
                            'url' => ('/market-subscriptions')
                        ])
                    );
                    $link = '<a href="' . ('/market-subscriptions') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
                    $box->style('success')
                        ->footer($link)
                        ->solid()
                        ->collapsable()
                        ->removable();
                    $column->append($box);
                });



                $row->column(6, function (Column $column) {
                    $lables = [];
                    $data = [];
                    $data_market = [];
                    for ($i = 0; $i < 12; $i++) {
                        $min = new Carbon();
                        $max = new Carbon();
                        $max->subMonths($i);
                        $min->subMonths(($i));

                        //get beginning and end of month
                        $min = $min->startOfMonth();
                        $max = $max->endOfMonth();
                        $lables[] = substr($min->monthName, 0, 3) . " - " . $min->year;
                        //formar Y-m-d
                        $min = $min->format('Y-m-d');
                        $max = $max->format('Y-m-d');

                        $data_market[] = MarketSubscription::whereBetween('start_date', [$min, $max])->count();
                    }
                    //reverse the arrays
                    $lables = array_reverse($lables);
                    $data = array_reverse($data);
                    $data_market = array_reverse($data_market);
                    $box = new Box(
                        'Market Info Subscriptions',
                        view('admin.widgets.widget-graph-3', [
                            'lables' => $lables,
                            'data' => $data,
                            'data_market' => $data_market,
                            'url' => ('orders'),
                        ])
                    );
                    $link = '<a href="' . admin_url('/market-subscriptions') . '" class="small-box-footer text-success">View More<i class="fa fa-arrow-circle-right"></i></a>';
                    $box->style('success')
                        ->footer($link)
                        ->solid()
                        ->collapsable()
                        ->removable();
                    $column->append($box);
                });


                $row->column(6, function (Column $column) {
                    $lables = [];
                    $data = [];

                    //percentage of market subscriptions
                    foreach (MarketPackage::all() as $key => $value) {
                        $lables[] = $value->name;
                        $data[] = MarketSubscription::where([
                            'package_id' => $value->id,
                            'status' => 1
                        ])
                            ->count();
                    }
                    $box = new Box(
                        'Market Subscriptions - By Packages',
                        view('admin.widgets.widget-graph-2', [
                            'lables' => $lables,
                            'data' => $data,
                            'url' => ('orders')
                        ])
                    );
                    $link = '<a href="' . admin_url('market-packages') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
                    $box->style('success')
                        ->footer($link)
                        ->solid()
                        ->collapsable()
                        ->removable();
                    $column->append($box);
                });


                $row->column(6, function (Column $column) {
                    $lables = [];
                    $data = [];
                    $data_market = [];
                    $data_weather = [];
                    $data_insurance = [];
                    for ($i = 0; $i < 12; $i++) {
                        $min = new Carbon();
                        $max = new Carbon();
                        $max->subMonths($i);
                        $min->subMonths(($i));

                        //get beginning and end of month
                        $min = $min->startOfMonth();
                        $max = $max->endOfMonth();
                        $lables[] = substr($min->monthName, 0, 3) . " - " . $min->year;
                        //formar Y-m-d
                        $min = $min->format('Y-m-d');
                        $max = $max->format('Y-m-d');
                        $data_weather[] = WeatherSubscription::whereBetween('start_date', [$min, $max])->count();
                    }
                    //reverse the arrays
                    $lables = array_reverse($lables);
                    $data_weather = array_reverse($data_weather);
                    $box = new Box(
                        'Weather Subscriptions',
                        view('admin.widgets.widget-graph-5', [
                            'lables' => $lables,
                            'data' => $data,
                            'data_weather' => $data_weather,
                            'url' => ('orders'),
                        ])
                    );
                    $link = '<a href="' . admin_url('weather-subscriptions') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
                    $box->style('success')
                        ->footer($link)
                        ->solid()
                        ->collapsable()
                        ->removable();
                    $column->append($box);
                });

                $row->column(6, function (Column $column) {
                    $lables = [];
                    $data = [];

                    $freq = [
                        /*                         'trial' => 'Trial',
 */
                        'weekly' => 'Weekly',
                        'monthly' => 'Monthly',
                        'yearly' => 'Yearly',
                    ];

                    //percentage of market subscriptions
                    foreach ($freq as $key => $value) {
                        $lables[] = $value;
                        $data[] = WeatherSubscription::where([
                            'frequency' => $key,
                            'status' => 1
                        ])
                            ->count();
                    }
                    $box = new Box(
                        'Active Weather Subscriptions - By Frequency',
                        view('admin.widgets.widget-graph-6', [
                            'lables' => $lables,
                            'data' => $data,
                            'url' => ('orders')
                        ])
                    );
                    $link = '<a href="' . admin_url('market-packages') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
                    $box->style('success')
                        ->footer($link)
                        ->solid()
                        ->collapsable()
                        ->removable();
                    $column->append($box);
                });


                /*    $row->column(6, function (Column $column) {
                    $lables = [];
                    $data = [];
                    $data_market = [];
                    $data_weather = [];
                    $data_insurance = [];
                    for ($i = 0; $i < 12; $i++) {
                        $min = new Carbon();
                        $max = new Carbon();
                        $max->subMonths($i);
                        $min->subMonths(($i));

                        //get beginning and end of month
                        $min = $min->startOfMonth();
                        $max = $max->endOfMonth();
                        $lables[] = substr($min->monthName, 0, 3) . " - " . $min->year;
                        //formar Y-m-d
                        $min = $min->format('Y-m-d');
                        $max = $max->format('Y-m-d');

                        $data_insurance[] = InsuranceSubscription::whereBetween('created_at', [$min, $max])->count();
                    }
                    //reverse the arrays
                    $lables = array_reverse($lables);
                    $data = array_reverse($data);
                    $data_insurance = array_reverse($data_insurance);
                    $box = new Box(
                        'Insurance Subscriptions',
                        view('admin.widgets.widget-graph-4', [
                            'lables' => $lables,
                            'data' => $data,
                            'data_market' => $data_market,
                            'data_weather' => $data_weather,
                            'data_insurance' => $data_insurance,
                            'url' => ('orders'),
                        ])
                    );
                    $link = '<a href="' . admin_url('insurance-subscriptions') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
                    $box->style('success')
                        ->footer($link)
                        ->solid()
                        ->collapsable()
                        ->removable();
                    $column->append($box);
                }); */
            })
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

                        $ents = Enterprise::where([])
                            ->limit(10)
                            ->get();
                        $max_days_ago = 30;
                        $data = [];
                        $now = Carbon::now();
                        for ($i = 0; $i < $max_days_ago; $i++) {
                            $start_date = $now->copy()->subDays($i);
                            $end_date = $start_date->copy()->addDays(1);
                            foreach ($ents as $key => $value) {
                                //where due_to_date is between start_date and end_date
                                $price = ItemPrice::where([
                                    'item_id' => $value->id
                                ])
                                    ->whereBetween('created_at', [$start_date, $end_date])
                                    ->orderBy('created_at', 'desc')
                                    ->first();
                                $price_text = 0;
                                if ($price != null) {
                                    $price_text = $price->price;
                                }
                                $data[$value->id][] = $price_text;
                            }
                        }

                        foreach ($ents as $key => $value) {
                            $data[] = [
                                'type' => 'line',
                                'label' => $value->name,
                                'data' => $value->value,
                            ];
                        }

                        $recent_market_subscriptions = MarketSubscription::where([])
                            ->orderBy('created_at', 'desc')
                            ->limit(10)
                            ->get();

                        $box = new Box(
                            'Active Market subscriptions',
                            view('admin.widgets.home-market-subscriptions', [
                                'data' => $recent_market_subscriptions,
                                'url' => ('/')
                            ])
                        );
                        $link = '<a href="' . ('market-subscriptions') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
                        $box->style('success')
                            ->footer($link)
                            ->solid()
                            ->collapsable();
                        $column->append($box);
                    });

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

                        $ents = Enterprise::where([])
                            ->limit(10)
                            ->get();
                        $max_days_ago = 30;
                        $data = [];
                        $now = Carbon::now();
                        for ($i = 0; $i < $max_days_ago; $i++) {
                            $start_date = $now->copy()->subDays($i);
                            $end_date = $start_date->copy()->addDays(1);
                            foreach ($ents as $key => $value) {
                                //where due_to_date is between start_date and end_date
                                $price = ItemPrice::where([
                                    'item_id' => $value->id
                                ])
                                    ->whereBetween('created_at', [$start_date, $end_date])
                                    ->orderBy('created_at', 'desc')
                                    ->first();
                                $price_text = 0;
                                if ($price != null) {
                                    $price_text = $price->price;
                                }
                                $data[$value->id][] = $price_text;
                            }
                        }

                        foreach ($ents as $key => $value) {
                            $data[] = [
                                'type' => 'line',
                                'label' => $value->name,
                                'data' => $value->value,
                            ];
                        }

                        $weather_subscriptions = WeatherSubscription::where([])
                            ->orderBy('created_at', 'desc')
                            ->limit(10)
                            ->get();

                        $box = new Box(
                            'Weather subscriptions',
                            view('admin.widgets.home-market-subscriptions', [
                                'data' => $weather_subscriptions,
                                'url' => ('/')
                            ])
                        );
                        $link = '<a href="' . ('weather-subscriptions') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
                        $box->style('success')
                            ->footer($link)
                            ->solid()
                            ->collapsable();
                        $column->append($box);
                    });

                    /*  $row->column(3, function (Column $column) {
                    $data = [];
                    $data[] = [
                        'title' => 'Weather subscriptions',
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

                    $ents = Enterprise::where([])
                        ->limit(10)
                        ->get();
                    $max_days_ago = 30;
                    $data = [];
                    $now = Carbon::now();
                    for ($i = 0; $i < $max_days_ago; $i++) {
                        $start_date = $now->copy()->subDays($i);
                        $end_date = $start_date->copy()->addDays(1);
                        foreach ($ents as $key => $value) {
                            //where due_to_date is between start_date and end_date
                            $price = ItemPrice::where([
                                'item_id' => $value->id
                            ])
                                ->whereBetween('created_at', [$start_date, $end_date])
                                ->orderBy('created_at', 'desc')
                                ->first();
                            $price_text = 0;
                            if ($price != null) {
                                $price_text = $price->price;
                            }
                            $data[$value->id][] = $price_text;
                        }
                    }

                    foreach ($ents as $key => $value) {
                        $data[] = [
                            'type' => 'line',
                            'label' => $value->name,
                            'data' => $value->value,
                        ];
                    }

                    $box = new Box(
                        'System Users',
                        view('admin.widgets.widget-graph-1', [
                            'data' => $data,
                            'url' => ('/')
                        ])
                    );
                    $link = '<a href="' . ('/') . '" class="small-box-footer text-success">View More <i class="fa fa-arrow-circle-right"></i></a>';
                    $box->style('success')
                        ->footer($link)
                        ->solid()
                        ->collapsable()
                        ->removable();
                    $column->append($box);
                });
 */




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
