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
    $router->resource('users', UserController::class);
    $router->resource('organisations', OrganisationController::class);
    $router->resource('trainings', TrainingController::class);
    $router->resource('training-topics', TrainingTopicController::class);
    $router->resource('e-learning-resources', ELearningResourceController::class);
    $router->resource('training-subtopics', TrainingSubtopicController::class);
    $router->resource('countries', CountryController::class);
    $router->resource('e-learning-courses', ELearningCourseController::class);
    $router->resource('training-resources', TrainingResourceCourseController::class);
    $router->resource('training-sessions', TrainingSessionCourseController::class);
    $router->resource('farmer-groups', FarmerGroupController::class);
    $router->resource('farmers', FarmerController::class);
    $router->resource('gens', GenController::class);
    $router->resource('organisation-joining-requests', OrganisationJoiningRequestController::class);
    $router->resource('resource-categories', ResourceCategoryController::class);

    $router->resource('farmer-questions', FarmerQuestionController::class);
    $router->resource('farmer-question-answers', FarmerQuestionAnswerController::class);
    $router->resource('product-categories', ProductCategoryController::class);
    $router->resource('districts', DistrictModelController::class);
});
