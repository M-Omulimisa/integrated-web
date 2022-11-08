<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Users\Role;
use App\Models\Settings\Country;
use App\Models\Settings\SystemModule;
use App\Models\Settings\Location;
use App\Models\Settings\Enterprise;
use App\Models\Settings\Language;
use App\Models\Settings\Season;
use App\Models\Organisations\Organisation;
use App\Models\Organisations\OrganisationUserPosition;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $dashboard = new Request;

        $dashboard->system_users = User::whereNull('organisation_id')->orWhereNull('microfinance_id')->count();
        $dashboard->roles = Role::count();
        $dashboard->system_modules = SystemModule::count();
        $dashboard->countries = Country::count();
        $dashboard->locations = Location::count();
        $dashboard->enterprises = Enterprise::count();
        $dashboard->language = Language::count();
        $dashboard->organisations = Organisation::count();
        $dashboard->organisation_users = OrganisationUserPosition::count();
        $dashboard->seasons = Season::count();

        return view('home', compact('dashboard'));
    }
}
