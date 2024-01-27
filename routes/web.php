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

Auth::routes();

//after complete backend
Route::group(['middleware' => 'auth'], function () {
    //All the routes that belongs to the group goes here
    Route::get('dashboard', function () {
    });

    //backend ROutes
    Route::get('/home', [HomeController::class, 'home'])->name('backend.home');
    Route::get('/user/home', [HomeController::class, 'user_home'])->name('user.backend.home');
    Route::resource('audits', AuditController::class);
    Route::resource('users', UserController::class);
    Route::resource('application', ApplicationController::class);
    Route::resource('group', GroupController::class);
    Route::resource('field', FieldController::class);
    Route::resource('notifications', NotificationController::class);
    Route::get('customedit/notifications/{id}', [NotificationController::class, 'custom_edit'])->name('notification.custom.edit');
    Route::get('filters/destroy/{id}', [NotificationController::class, 'filtersDestroy'])->name('filters.destroy');
    Route::get('create-notification', [NotificationController::class, 'createNotification'])->name('create.notification');
    Route::resource('role', RoleController::class);
    Route::resource('multiplerole', MultipleroleController::class);
    Route::resource('user-application', UserApplicationController::class);
    Route::get('user-application/list/{id}', [UserApplicationController::class, 'userapplication_list'])->name('userapplication.list');
    Route::get('user-application/edit/{id}', [UserApplicationController::class, 'userapplication_edit'])->name('userapplication.edit');
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
    Route::get('/get-report-application', [ReportController::class, 'getReportApplication'])->name('get.report.application');
    Route::get('/send-report-application/{id}', [ReportController::class, 'sendReportApplication'])->name('report.report.application');
    Route::get('/search-report', [ReportController::class, 'searchReport'])->name('search.report');
    Route::get('/view-save-report/{id}', [ReportController::class, 'viewSaveReport'])->name('view.save.report');
    Route::get('/delete-report/{id}', [ReportController::class, 'deleteReport'])->name('delete.report');
    Route::post('/store-report', [ReportController::class, 'storeReport'])->name('store.report');
    Route::post('/store-cert-report', [ReportController::class, 'storeCertReport'])->name('store.cert.report');
    Route::get('/view-chart/{id}', [ReportController::class, 'viewChart'])->name('view.chart');
    Route::get('/edit-chart/{id}', [ReportController::class, 'editChart'])->name('edit.chart');
});
Route::get('/get-file', [IntegrationController::class, 'getFile'])->name('get.file');
Route::get('/get-csv-data', [IntegrationController::class, 'getCsvData'])->name('get.csv.data');