<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\backend\AuditController;
use App\Http\Controllers\backend\ApplicationController;
use App\Http\Controllers\backend\GroupController;
use App\Http\Controllers\backend\UserController;
use App\Http\Controllers\backend\FieldController;
use App\Http\Controllers\backend\RoleController;
use App\Http\Controllers\backend\MultipleroleController;
use App\Http\Controllers\backend\UserApplicationController;
use App\Http\Controllers\backend\ImportController;
use App\Http\Controllers\backend\NotificationController;
use App\Http\Controllers\backend\AjaxController;
use App\Http\Controllers\backend\LogController;
use App\Http\Controllers\backend\CustomWorkflowController;
use App\Http\Controllers\backend\DashboardController;
use App\Http\Controllers\backend\IntegrationController;
use App\Http\Controllers\backend\ReportController;

// use App\Http\Controllers\backend\Custom;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/back', function () {
    return redirect()->back();
})->name('back');
Auth::routes();

//after complete backend
Route::group(['middleware' => 'auth'], function () {
    //All the routes that belongs to the group goes here
    // Route::get('dashboard', function () {
    // });

    //backend ROutes
    Route::get('/home', [HomeController::class, 'home'])->name('backend.home');
    Route::get('/user/home', [HomeController::class, 'user_home'])->name('user.backend.home');
    Route::resource('audits', AuditController::class);
    Route::resource('users', UserController::class);
    Route::resource('application', ApplicationController::class);
    Route::resource('group', GroupController::class);
    Route::resource('field', FieldController::class);
    Route::resource('notifications', NotificationController::class);
    Route::resource('dashboard', DashboardController::class);
    Route::post('/get-report', [DashboardController::class, 'getReport'])->name('get.report');
    Route::get('notification/add', [NotificationController::class, 'addNotification'])->name('add.notification');
    Route::get('customedit/notifications/{id}', [NotificationController::class, 'custom_edit'])->name('notification.custom.edit');
    Route::get('filters/destroy/{id}', [NotificationController::class, 'filtersDestroy'])->name('filters.destroy');
    Route::get('create-notification', [NotificationController::class, 'createNotification'])->name('create.notification');
    Route::resource('role', RoleController::class);
    Route::resource('multiplerole', MultipleroleController::class);
    Route::resource('user-application', UserApplicationController::class);
    Route::get('user-application/list/{id}', [UserApplicationController::class, 'userapplication_list'])->name('userapplication.list');
    Route::post('update/edit/{id}', [UserApplicationController::class, 'updateEdit'])->name('update.edit');
    Route::get('user-application/edit/{id}', [UserApplicationController::class, 'userapplication_edit'])->name('userapplication.edit');
    Route::get('user-application/user/action/{id}/{triggerId}', [UserApplicationController::class, 'userapplication_userAction'])->name('userapplication.user.action');
    Route::post('change/forder', [AjaxController::class, 'change_forder'])->name('change.forder');
    Route::get('user-application/index/{id}', [UserApplicationController::class, 'userapplication_index'])->name('userapplication.index');
    Route::post('user-application/index/save', [UserApplicationController::class, 'userapplication_index_save'])->name('userapplication.index.save');




    Route::delete('attachment/delete/{id}', [ApplicationController::class, 'attachment_delete'])->name('attachment.delete');

    //import routes
    Route::get('user-application/import/{id}', [ImportController::class, 'getImport'])->name('csv.import');
    Route::post('user-application/import_parse', [ImportController::class, 'parseImport'])->name('import_parse');
    Route::post('user-application/import_process', [ImportController::class, 'processImport'])->name('import_process');

    //logs functionality
    Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
    //

    //for workflow
    Route::group(['middleware' => ['auth']], function () {
        \the42coders\Workflows\Workflows::routes();
    });
    //Custom Workflow
    Route::resource('custom-workflow', CustomWorkflowController::class);
    Route::get('/custom-workflow-triggerButtonShow/{id}', [CustomWorkflowController::class, 'triggerButtonShow'])->name('triggerButtonShow');
    Route::get('/custom-workflow-saveMail', [CustomWorkflowController::class, 'saveMail'])->name('saveMail');
    Route::get('/custom-workflow-evaluate-content', [CustomWorkflowController::class, 'evaluateContent'])->name('evaluate.content');
    Route::get('/custom-workflow-evaluateRules-destroy/{id}', [CustomWorkflowController::class, 'evaluateRulesDestroy'])->name('evaluateRules.destroy');
    Route::get('/custom-workflow-UpdateContent-store', [CustomWorkflowController::class, 'UpdateContentStore'])->name('updateContent.store');
    Route::get('/custom-workflow-userAction-store', [CustomWorkflowController::class, 'userActionStore'])->name('userAction.store');
    Route::post('/custom-workflow-transition', [CustomWorkflowController::class, 'transitionStore'])->name('transition.store');
    Route::get('/transition-destroy/{id}', [CustomWorkflowController::class, 'transitionDestroy'])->name('transition.destroy');
    Route::get('/workflow-logs/{id}', [CustomWorkflowController::class, 'workflowLogsShow'])->name('workflow.logs.show');
    Route::get('/get-task/{id}', [CustomWorkflowController::class, 'getTaskByElementId'])->name('get.task');


    // IntegrationController
    Route::get('/data-feed', [IntegrationController::class, 'dataFeed'])->name('data.feed');
    Route::get('/data-imports', [IntegrationController::class, 'dataImports'])->name('data.imports');
    Route::get('/{id}/upload-form', [IntegrationController::class, 'showForm'])->name('show.form');
    Route::get('/{id}/upload-url', [IntegrationController::class, 'showUrl'])->name('show.url');
    Route::post('/import-upload', [IntegrationController::class, 'importUpload'])->name('import.upload');
    Route::post('/url-upload', [IntegrationController::class, 'urlUpload'])->name('url.upload');
    Route::post('/url-local-upload', [IntegrationController::class, 'urlLocalUpload'])->name('url.local.upload');
    Route::get('/import-review/{path}/{id}', [IntegrationController::class, 'reviewImport'])->name('review.import');
    Route::post('/process-import', [IntegrationController::class, 'processImport'])->name('process.import');

    // ReportController
    Route::get('/get-view', [ReportController::class, 'getView'])->name('get.view');
    Route::get('/send-report-application', [ReportController::class, 'sendReportApplication'])->name('report.report.application');
    Route::get('/back-report-application/{id}', [ReportController::class, 'backReportApplication'])->name('back.report.application');
    Route::get('/search-report', [ReportController::class, 'searchReport'])->name('search.report');
    Route::get('/view-save-report/{id}', [ReportController::class, 'viewSaveReport'])->name('view.save.report');
    Route::get('/delete-report/{id}', [ReportController::class, 'deleteReport'])->name('delete.report');
    Route::post('/store-report', [ReportController::class, 'storeReport'])->name('store.report');
    Route::get('/store-cert-report', [ReportController::class, 'storeCertReport'])->name('store.cert.report');
    Route::get('/view-chart/{id}', [ReportController::class, 'viewChart'])->name('view.chart');
    Route::get('/edit-chart/{id}', [ReportController::class, 'editChart'])->name('edit.chart');
    Route::get('/route-to-handle-filtered-data', [ReportController::class, 'handleFilteredDataRequest'])->name('route.to.handle');
    Route::get('/remove-from-session/{name}', [ReportController::class, 'removeFromSession'])->name('remove.from.session');
    Route::get('/remove-from-session-normal/{name}', [ReportController::class, 'removeFromSessionNormal'])->name('remove.from.session.normal');
});
Route::get('/get-file', [IntegrationController::class, 'getFile'])->name('get.file');
Route::get('/get-csv-data', [IntegrationController::class, 'getCsvData'])->name('get.csv.data');
