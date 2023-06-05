@inject('request', 'Illuminate\Http\Request')
<ul class="navbar-nav" id="navbar-nav">
    <li class="menu-title"><span>Main Menu</span></li>
    
    <li class="nav-item">
        <a class="nav-link menu-link collapsed" href="{{ url('/') }}">
            <i data-feather="airplay"></i> <span>Dashboard</span>
        </a>
    </li>

    @php($farmers=$request->segment(1)=='farmers')
    <li class="nav-item">
        <a class="nav-link menu-link collapsed {{ $farmers?'active':'' }}" href="#sidebarFarmers" data-bs-toggle="collapse" role="button" aria-expanded="{{ $farmers?'true':'false' }}" aria-controls="sidebarFarmers">
            <i data-feather="users"></i> <span>Farmers</span>
        </a>
        <div class="collapse menu-dropdown {{ $farmers?'show':'' }}" id="sidebarFarmers">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="{{ route('farmers.farmers.index') }}" class="nav-link {{ $request->segment(2)=='farmers'?'active':'' }}"> Farmers </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('farmers.groups.index') }}" class="nav-link {{ $request->segment(2)=='groups'?'active':'' }}"> Farmer Groups </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('farmers.mapping.farmers') }}" class="nav-link {{ $request->segment(2)=='mapping'?'active':'' }}"> Mapping </a>
                </li>
            </ul>
        </div>
    </li> <!-- end Dashboard Menu -->

    @php($village_agents=$request->segment(1)=='village-agents')
    <li class="nav-item">
        <a class="nav-link menu-link collapsed {{ $village_agents?'active':'' }}" href="#sidebarVillageAgents" data-bs-toggle="collapse" role="button" aria-expanded="{{ $village_agents?'true':'false' }}" aria-controls="sidebarVillageAgents">
            <i class="ri-team-line"></i> <span>Agents</span>
        </a>
        <div class="collapse menu-dropdown {{ $village_agents?'show':'' }}" id="sidebarVillageAgents">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="{{ route('village-agents.agents.index') }}" class="nav-link {{ $village_agents && $request->segment(2)=='agents'?'active':'' }}"> Profiles</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('village-agents.mapping.agents') }}" class="nav-link {{ $village_agents && $request->segment(2)=='mapping'?'active':'' }}"> Mapping </a>
                </li>
            </ul>
        </div>
    </li> <!-- end Dashboard Menu -->
    
    @php($extension=$request->segment(1)=='extension-officers')
    <li class="nav-item">
        <a class="nav-link menu-link collapsed {{ $extension?'active':'' }}" href="#sidebarExtension" data-bs-toggle="collapse" role="button" aria-expanded="{{ $extension?'true':'false' }}" aria-controls="sidebarExtension">
            <i class="ri-team-line"></i> <span>Extension Officers</span>
        </a>
        <div class="collapse menu-dropdown {{ $extension?'show':'' }}" id="sidebarExtension">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="{{ route('extension-officers.officers.index') }}" class="nav-link {{ $extension && $request->segment(2)=='agents'?'active':'' }}"> Profiles</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('extension-officers.mapping.officers') }}" class="nav-link {{ $extension && $request->segment(2)=='mapping'?'active':'' }}"> Mapping </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('extension-officers.positions.index') }}" class="nav-link {{ $extension && $request->segment(2)=='positions'?'active':'' }}"> Positions </a>
                </li>
            </ul>
        </div>
    </li> <!-- end Dashboard Menu -->
 

    @php($organisations=$request->segment(1)=='organisations')
    <li class="nav-item">
        <a class="nav-link menu-link collapsed {{ $organisations?'active':'' }}" href="#sidebarOrganisation" data-bs-toggle="collapse" role="button" aria-expanded="{{ $organisations?'true':'false' }}" aria-controls="sidebarOrganisation">
            <i data-feather="briefcase"></i> <span>Organisations</span>
        </a>
        <div class="collapse menu-dropdown {{ $organisations?'show':'' }}" id="sidebarOrganisation">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="{{ route('organisations.organisations.index') }}" class="nav-link {{ $organisations && $request->segment(2)=='partners'?'active':'' }}"> Organisations</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('organisations.users.index') }}" class="nav-link {{ $organisations && $request->segment(2)=='users'?'active':'' }}"> Officers/Users </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link"> Officer Supervision </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link"> Agent to Officers </a>
                </li>
                @can('list_organisation_settings')
                    <li class="nav-item">
                        <a href="{{ route('organisations.positions.index') }}" class="nav-link {{ $organisations && $request->segment(2)=='positions'?'active':'' }}"> Positions </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('organisations.permissions.index') }}" class="nav-link {{ $organisations && $request->segment(2)=='permissions'?'active':'' }}"> Permissions </a>
                    </li>
                @endcan
            </ul>
        </div>
    </li> <!-- end Dashboard Menu -->

    @php($insurance=$request->segment(1)=='insurance')
    <li class="nav-item">
        <a class="nav-link menu-link collapsed {{ $insurance?'active':'' }}" href="#sidebarInsurance" data-bs-toggle="collapse" role="button" aria-expanded="{{ $insurance?'true':'false' }}" aria-controls="sidebarInsurance">
            <i data-feather="umbrella"></i> <span>Insurance</span>
        </a>
        <div class="collapse menu-dropdown {{ $insurance?'show':'' }}" id="sidebarInsurance">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="{{ route('insurance.subscriptions.index') }}" class="nav-link"> Subscriptions </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('insurance.agent-earnings.index') }}" class="nav-link"> Agent Earnings </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('insurance.farmer-compensations.index') }}" class="nav-link"> Compensation </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="{{ route('insurance.insurance-agents.index') }}" class="nav-link"> Agents </a>
                </li> --}}
                {{-- <li class="nav-item">
                    <a href="{{ route('insurance.companies.index') }}" class="nav-link"> Companies </a>
                </li> --}}
                <li class="nav-item">
                    <a href="{{ route('insurance.loss-management.index') }}" class="nav-link"> Loss Management </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="{{ route('insurance.agent-commissions.index') }}" class="nav-link"> Agent Commission </a>
                </li> --}}
                <li class="nav-item">
                    <a href="{{ route('insurance.calculator.index') }}" class="nav-link"> Calculator </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('insurance.premium-options.index') }}" class="nav-link"> Premium Options </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('insurance.full-coverage-rates.index') }}" class="nav-link"> Full coverage Rates </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('insurance.insurance-periods.index') }}" class="nav-link"> Insurance Window </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('insurance.transactions.index') }}" class="nav-link"> Transactions </a>
                </li>
            </ul>
        </div>
    </li> <!-- end Insurance Menu -->

    @php($questions=$request->segment(1)=='questions')
    <li class="nav-item">
        <a class="nav-link menu-link collapsed {{ $questions?'active':'' }}" href="#sidebarQuestions" data-bs-toggle="collapse" role="button" aria-expanded="{{ $questions?'true':'false' }}" aria-controls="sidebarQuestions">
            <i class="ri-questionnaire-line"></i> <span>Questions</span>
        </a>
        <div class="collapse menu-dropdown {{ $questions?'show':'' }}" id="sidebarQuestions">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="{{ route('questions.questions.index') }}" class="nav-link"> Farmer Questions </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('questions.responses.index') }}" class="nav-link"> Responses </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('questions.mapping.questions') }}" class="nav-link"> Mapping </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link"> Incoming </a>
                </li>
            </ul>
        </div>
    </li> <!-- end Dashboard Menu -->

    @php($alerts=$request->segment(1)=='alerts')
    <li class="nav-item">
        <a class="nav-link menu-link collapsed {{ $alerts?'active':'' }}" href="#sidebarOutbreaks" data-bs-toggle="collapse" role="button" aria-expanded="{{ $alerts?'true':'false' }}" aria-controls="sidebarOutbreaks">
            <i data-feather="bell"></i> <span>Alerts</span>
        </a>
        <div class="collapse menu-dropdown {{ $alerts?'show':'' }}" id="sidebarOutbreaks">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="{{ route('alerts.alerts.index') }}" class="nav-link"> Alerts </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('alerts.outbreaks.index') }}" class="nav-link"> Outbreaks </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('alerts.mapping.alerts') }}" class="nav-link"> Heat map </a>
                </li>
            </ul>
        </div>
    </li> <!-- end Dashboard Menu -->

    @php($loans=$request->segment(1)=='input-loans')
    <li class="nav-item">
        <a class="nav-link menu-link collapsed {{ $loans?'active':'' }}" href="#sidebarInputLoans" data-bs-toggle="collapse" role="button" aria-expanded="{{ $loans?'true':'false' }}" aria-controls="sidebarInputLoans">
            <i data-feather="dollar-sign"></i> <span>Input Loans</span>
        </a>
        <div class="collapse menu-dropdown {{ $loans?'show':'' }}" id="sidebarInputLoans">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="{{ route('input-loans.projects.index') }}" class="nav-link"> Projects </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('input-loans.input-requests.index') }}" class="nav-link"> Input Requests </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('input-loans.lpos.index') }}" class="nav-link"> Orders </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('input-loans.loan-repayments.index') }}" class="nav-link"> Repayments </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('input-loans.microfinances.index') }}" class="nav-link"> Microfinances </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link"> Agents to MFI </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('input-loans.distributors.index') }}" class="nav-link"> Distributors </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('input-loans.buyers.index') }}" class="nav-link"> Buyers </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('input-loans.loan-settings.index') }}" class="nav-link"> Loan Limts </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('input-loans.yield-estimations.index') }}" class="nav-link"> Yield Estimations </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('input-loans.lpo-settings.index') }}" class="nav-link"> LPO Settings </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('input-loans.loan-charges.index') }}" class="nav-link"> Loan Charges </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('input-loans.input-commission-rates.index') }}" class="nav-link"> Input Commission</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('input-loans.input-prices.index') }}" class="nav-link"> Input Prices </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('input-loans.output-prices.index') }}" class="nav-link"> Output Prices </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link"> Request Status Alerts </a>
                </li>
            </ul>
        </div>
    </li> <!-- end Dashboard Menu -->

    @php($trainings=$request->segment(1)=='trainings')
    <li class="nav-item">
        <a class="nav-link menu-link collapsed {{ $trainings?'active':'' }}" href="#sidebarTrainings" data-bs-toggle="collapse" role="button" aria-expanded="{{ $trainings?'true':'false' }}" aria-controls="sidebarTrainings">
            <i class="ri-parent-line"></i> <span>Training</span>
        </a>
        <div class="collapse menu-dropdown {{ $trainings?'show':'' }}" id="sidebarTrainings">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="{{ route('trainings.trainings.index') }}" class="nav-link"> Trainings </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('trainings.resources.index') }}" class="nav-link"> Resources </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('trainings.topics.index') }}" class="nav-link"> Topics </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('trainings.sub-topics.index') }}" class="nav-link"> Subtopics/Activities </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="{{ route('trainings.reports.index') }}" class="nav-link"> Reports </a>
                </li> --}}
            </ul>
        </div>
    </li> <!-- end Dashboard Menu -->

    @php($elearning=$request->segment(1)=='e-learning')
    <li class="nav-item">
        <a class="nav-link menu-link collapsed" href="#sidebarElearning" data-bs-toggle="collapse" role="button" aria-expanded="{{ $elearning?'true':'false' }}" aria-controls="sidebarElearning">
            <i class="ri-book-open-line"></i> <span>E-Learning</span>
        </a>
        <div class="collapse menu-dropdown {{ $elearning?'show':'' }}" id="sidebarElearning">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="{{ route('e-learning.courses.index') }}" class="nav-link"> Courses </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('e-learning.instructors.index') }}" class="nav-link"> Instructors </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('e-learning.students.index') }}" class="nav-link"> Students </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('e-learning.instructions.index') }}" class="nav-link"> Default Instructions </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('e-learning.messages.index') }}" class="nav-link"> Default Messages </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('e-learning.system-out-calls.index') }}" class="nav-link"> Callback Time </a>
                </li>
            </ul>
        </div>
    </li> <!-- end Dashboard Menu -->

    @php($market=$request->segment(1)=='market')
    <li class="nav-item">
        <a class="nav-link menu-link collapsed {{ $market?'active':'' }}" href="#sidebarMarketInfo" data-bs-toggle="collapse" role="button" aria-expanded="{{ $market?'true':'false' }}" aria-controls="sidebarMarketInfo">
            <i class="ri-shopping-cart-2-line"></i> <span>Market Info</span>
        </a>
        <div class="collapse menu-dropdown {{ $market?'show':'' }}" id="sidebarMarketInfo">
            <ul class="nav nav-sm flex-column">
                {{-- <li class="nav-item">
                    <a href="{{ route('market.subscription-keyword-prices.index') }}" class="nav-link"> Keyword Pricing (by subscription) </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('market.request-keyword-prices.index') }}" class="nav-link"> Keyword Pricing (on request) </a>
                </li> --}}
                <li class="nav-item">
                    <a href="{{ route('market.subscriptions.index') }}" class="nav-link"> Subscriptions </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('market.outbox.index') }}" class="nav-link"> Outbox </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('market.transactions.index') }}" class="nav-link"> Transactions </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('market.commodity-prices.index') }}" class="nav-link"> Commodity Prices </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('market.commodities.index') }}" class="nav-link"> Commodities </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('market.markets.index') }}" class="nav-link"> Markets </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('market.packages.index') }}" class="nav-link"> Packages </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="{{ url('/') }}" class="nav-link"> Reports </a>
                </li> --}}
            </ul>
        </div>
    </li> <!-- end Dashboard Menu -->

    @php($weather=$request->segment(1)=='weather-info')
    <li class="nav-item">
        <a class="nav-link menu-link collapsed {{ $weather?'active':'' }}" href="#sidebarWeather" data-bs-toggle="collapse" role="button" aria-expanded="{{ $weather?'true':'false' }}" aria-controls="sidebarWeather">
            <i data-feather="cloud-drizzle"></i> <span>Weather Info</span>
        </a>
        <div class="collapse menu-dropdown {{ $weather?'show':'' }}" id="sidebarWeather">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="{{ route('weather-info.subscriptions.index') }}" class="nav-link"> Subscriptions </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('weather-info.triggers.index') }}" class="nav-link"> Triggers </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('weather-info.transactions.index') }}" class="nav-link"> Transactions </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('weather-info.outbox.index') }}" class="nav-link"> Outbox </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('weather-info.conditions.index') }}" class="nav-link"> Conditions </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="{{ route('weather-info.subscriptions.index') }}" class="nav-link"> Reports </a>
                </li> --}}
            </ul>
        </div>
    </li> <!-- end Dashboard Menu -->

    @php($settings=$request->segment(1)=='settings')
    <li class="nav-item">
        <a class="nav-link menu-link collapsed {{ $settings?'active':'' }}" href="#sidebarConfigurations" data-bs-toggle="collapse" role="button" aria-expanded="{{ $settings?'true':'false' }}" aria-controls="sidebarConfigurations">
            <i data-feather="settings"></i> <span>General Settings</span>
        </a>
        <div class="collapse menu-dropdown {{ $settings?'show':'' }}" id="sidebarConfigurations">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="{{ route('settings.modules.index') }}" class="nav-link"> Modules </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('settings.countries.index') }}" class="nav-link"> Countries </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('settings.country-units.index') }}" class="nav-link"> Country Units </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('settings.country-providers.index') }}" class="nav-link"> Country Providers </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('settings.country-modules.index') }}" class="nav-link"> Country Access </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('settings.locations.index') }}" class="nav-link"> Locations </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('settings.languages.index') }}" class="nav-link"> Languages </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('settings.units.index') }}" class="nav-link"> Units </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('settings.enterprises.index') }}" class="nav-link"> Enterprises </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('settings.enterprise-varieties.index') }}" class="nav-link"> Enterprise Varities </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('settings.enterprise-types.index') }}" class="nav-link"> Enterprise Types </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('settings.seasons.index') }}" class="nav-link"> Seasons </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('settings.keywords.index') }}" class="nav-link"> Keyword </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('settings.success-responses.index') }}" class="nav-link"> Success Responses </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('settings.failure-responses.index') }}" class="nav-link"> Failure Responses </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('settings.agro-products.index') }}" class="nav-link"> Agro Products </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('settings.commission-rankings.index') }}" class="nav-link"> Commission Rankings </a>
                </li>
            </ul>
        </div>
    </li> <!-- end Dashboard Menu -->

    @php($users=$request->segment(1)=='user-management')
    <li class="nav-item">
        <a class="nav-link menu-link collapsed {{ $users?'active':'' }}" href="#sidebarUserMgt" data-bs-toggle="collapse" role="button" aria-expanded="{{ $users?'true':'false' }}" aria-controls="sidebarUserMgt">
            <i class="ri-group-line"></i> <span>User Management</span>
        </a>
        <div class="collapse menu-dropdown {{ $users?'show':'' }}" id="sidebarUserMgt">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="{{ route('user-management.users.index') }}" class="nav-link {{ $users && $request->segment(2)=='users'?'active':'' }}"> Users </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user-management.roles.index') }}" class="nav-link {{ $users && $request->segment(2)=='roles'?'active':'' }}"> Roles </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user-management.permissions.index') }}" class="nav-link {{ $users && $request->segment(2)=='permissions'?'active':'' }}"> Permissions </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user-management.activity-logs.index') }}" class="nav-link {{ $users && $request->segment(2)=='activity-logs'?'active':'' }}"> Activity </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('user-management.sessions.index') }}" class="nav-link {{ $users && $request->segment(2)=='sessions'?'active':'' }}"> Sessions </a>
                </li>
            </ul>
        </div>
    </li> <!-- end Dashboard Menu -->

    <li class="nav-item">
        <a class="nav-link menu-link collapsed" href="{{ route('validations.phones.index') }}">
            <i data-feather="phone"></i> <span>Phone Validations</span>
        </a>
    </li>

</ul>