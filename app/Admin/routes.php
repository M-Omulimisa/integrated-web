<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->get('courses-stats', 'HomeController@stats')->name('home');
    $router->resource('users', UserController::class);
    $router->resource('organisations', OrganisationController::class);
    $router->resource('trainings', TrainingController::class);
    $router->resource('training-topics', TrainingTopicController::class);
    $router->resource('e-learning-resources', ELearningResourceController::class);
    $router->resource('training-subtopics', TrainingSubtopicController::class);
    $router->resource('countries', CountryController::class);
    //$router->resource('e-learning-courses', ELearningCourseController::class);
    $router->resource('training-resources', TrainingResourceCourseController::class);
    $router->resource('training-sessions', TrainingSessionCourseController::class);
    $router->resource('farmer-groups', FarmerGroupController::class);
    $router->resource('farmers', FarmerController::class);
    $router->resource('gens', GenController::class);
    $router->resource('organisation-joining-requests', OrganisationJoiningRequestController::class);
    $router->resource('resource-categories', ResourceCategoryController::class);
    $router->resource('financial-institutions', FinancialInstitutionController::class);
    $router->resource('requests', VendorController::class);
    $router->resource('products', ProductController::class);

    $router->resource('farmer-questions', FarmerQuestionController::class);
    $router->resource('farmer-questions-unanswered', FarmerQuestionController::class); // this is a custom route 
    $router->resource('farmer-questions', FarmerQuestionController::class);
    $router->resource('farmer-question-answers', FarmerQuestionAnswerController::class);
    $router->resource('product-categories', ProductCategoryController::class);
    $router->resource('districts', DistrictModelController::class);
    $router->resource('subcounties', SubcountyModelController::class);
    $router->resource('parishes', ParishModelModelController::class);
    $router->resource('locations', LocationController::class);
    $router->resource('crops', CropsController::class);
    $router->resource('seasons', SeasonController::class);
    $router->resource('insurance-markup', MarkupController::class);
    $router->resource('regions', RegionController::class);
    $router->resource('insurance-subscriptions', InsuranceSubscriptionController::class);
    $router->resource('insurance-premium-options', InsurancePremiumOptionController::class);
    $router->resource('orders', OrderController::class);
    $router->resource('market-packages', MarketPackageController::class);
    $router->resource('market-package-messages', MartketPackageMessageController::class);
    $router->resource('enterprises', EnterpriseController::class);
    $router->resource('market-subscriptions', MarketSubscriptionController::class);
    $router->resource('market-subscriptions-expired', MarketSubscriptionController::class);
    $router->resource('market-subscriptions-not-paid', MarketSubscriptionController::class);
    $router->resource('market-subscriptions-all', MarketSubscriptionController::class);
    $router->resource('market-outboxes', MarketOutboxController::class);
    $router->resource('subscription-payments', SubscriptionPaymentController::class);
    $router->resource('weather-conditions', WeatherConditionController::class);
    $router->resource('market-subscription-payments', MarketPaymentSubscriptionController::class);
    $router->resource('insurance-subscription-payments', InsurancePaymentSubscriptionController::class);
    $router->resource('weather-subscription-payments', WeatherPaymentSubscriptionController::class);

    /*===============e-learning start=============*/
    $router->resource('e-learning-courses', OnlineCourseController::class);
    $router->resource('courses', OnlineCourseController::class);
    $router->resource('online-course-categories', OnlineCourseCategoryController::class);
    $router->resource('online-course-chapters', OnlineCourseChapterController::class);
    $router->resource('online-course-topics', OnlineCourseTopicController::class);
    $router->resource('online-course-students', OnlineCourseStudentController::class);
    $router->resource('online-course-lessons', OnlineCourseLessonController::class);
    $router->resource('student-batch-importers', OnlineCourseStudentBatchImporterController::class);
    $router->resource('languages', LanguageController::class);
    /*===============e-learning ends=============*/

    /*============== notifications start here =============*/
    $router->resource('notification-campaigns', NotificationCampaignController::class);
    $router->resource('notification-messages', NotificationMessageController::class);
    /*============== notifications ends here =============*/

    $router->resource('ussd-advisory-topics', UssdAdvisoryTopicController::class);
    $router->resource('ussd-advisory-questions', UssdAdvisoryQuestionController::class);
    $router->resource('ussd-languages', UssdLanguageController::class);
    $router->resource('ussd-question-options', UssdQuestionOptionController::class);
    $router->resource('ussd-advisory-messages', UssdAdvisoryMessageController::class);
    $router->resource('ussd-advisory-message-outboxes', UssdAdvisoryMessageOutboxController::class);
    $router->resource('calls', OnlineCourseAfricaTalkingCallController::class);
    $router->resource('online-course-menus', OnlineCourseMenuController::class);
    $router->resource('weather-subscriptions', WeatherSubscriptionController::class);
    $router->resource('weather-sms-translations', WeatherSmsTranslationController::class);
    $router->resource('damark-rercords', DamarkRercordController::class);

    $router->resource('translated-words', TranslatedWordController::class);
    $router->resource('item-prices', ItemPriceController::class);
    $router->resource('market-info-message-campaigns', MarketInfoMessageCampaignController::class);
    $router->resource('weather-outboxes', WeatherOutboxController::class);
});
