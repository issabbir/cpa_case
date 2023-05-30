<?php

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

Route::get('/', 'UserController@index')->name('login');

Route::post('/authorization/login', 'Auth\LoginController@authorization')->name('authorization.login');


Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    Route::get('/user/change-password', function () {
        return view('resetPassword');
    })->name('change-password');

    Route::post('/user/change-password', 'Auth\ResetPasswordController@resetPassword')->name('user.reset-password');
    Route::post('/report/render/{title}', 'Report\OraclePublisherController@render')->name('report');
    Route::get('/report/render/{title?}', 'Report\OraclePublisherController@render')->name('report-get');
    Route::post('/authorization/logout', 'Auth\LoginController@logout')->name('logout');

    // For News
    Route::get('/get-top-news', 'NewsController@getNews')->name('get-top-news');
    Route::get('/news-download/{id}', 'NewsController@downloadAttachment')->name('news-download');

    Route::get('/get-organization-option', 'cms\ReportController@getOrg')->name('get-organization-option');

    Route::get('/search-period-datatable-list', 'Secdbms\Fas\AccountsPeriodController@datatableList')->name('datatable-list');


        Route::group(['name' => 'cms', 'as' => 'cms.'], function () {

            Route::group(['name' => 'case-category', 'as' => 'case-category.'], function () {
                Route::get('/case-category', 'cms\setup\CaseCategoryController@index')->name('case-category-index');
                Route::get('/case-category/edit/{id}', 'cms\setup\CaseCategoryController@edit')->name('case-category-edit');
                Route::post('/case-category/edit/{id}', 'cms\setup\CaseCategoryController@updateCaseCategory')->name('updateCaseCategory');
                Route::post('/case-category', 'cms\setup\CaseCategoryController@storeCaseCategory')->name('storeCaseCategory');
                Route::post('/case-category-dataTable', 'cms\setup\CaseCategoryController@caseCategoryDatatable')->name('caseCategoryDatatable');
            });

            Route::group(['name' => 'court-info', 'as' => 'court-info.'], function () {
                Route::get('/court-info', 'cms\setup\CourtInformationController@index')->name('court-info-index');
                Route::get('/court-info/edit/{id}', 'cms\setup\CourtInformationController@edit')->name('court-info-edit');
                Route::post('/court-info/edit/{id}', 'cms\setup\CourtInformationController@updateCourtInformation')->name('updateCourtInformation');
                Route::post('/court-info', 'cms\setup\CourtInformationController@storeCourtInformation')->name('storeCourtInformation');
                Route::post('/court-info-dataTable', 'cms\setup\CourtInformationController@courtInformationDatatable')->name('courtInformationDatatable');
                Route::get('/organization-registration', 'cms\setup\CourtInformationController@organizationRegistration')->name('organization-registration');
                Route::post('/organization-registration', 'cms\setup\CourtInformationController@storeOrganization')->name('store-organization');
                Route::get('/ajax-organization-registration', 'cms\setup\CourtInformationController@ajaxStoreOrganization')->name('ajax-store-organization');
                Route::post('/organization-dataTable', 'cms\setup\CourtInformationController@organizationDatatable')->name('organizationDatatable');
                Route::get('/org-reg/edit/{id}', 'cms\setup\CourtInformationController@org_edit')->name('org-edit');
                Route::post('/organization-registration/edit/{id}', 'cms\setup\CourtInformationController@updateOrganization')->name('updateOrganization');
                Route::get('/get-org-name-ajax', 'cms\setup\CourtInformationController@get_org_name')->name('get-org-name');
                Route::get('/add-employee', 'cms\setup\CourtInformationController@storeEmployee')->name('add-employee');
                Route::post('/employee-dataTable', 'cms\setup\CourtInformationController@employeeDatatable')->name('employeeDatatable');
                Route::get('/emp-reg/edit', 'cms\setup\CourtInformationController@emp_edit')->name('emp-edit');
            });

            Route::group(['name' => 'case-info', 'as' => 'case-info.'], function () {
                Route::get('/case-info', 'cms\CaseInformationController@index')->name('case-info-index');
                Route::get('/party-data-remove', 'cms\CaseInformationController@removePartyData')->name('removePartyData');
                Route::get('/case-doc-remove', 'cms\CaseInformationController@caseDocRemove')->name('caseDocRemove');
                Route::get('/get-emp-data', 'cms\CaseInformationController@employees')->name('employees');
                Route::get('/get-emp-data/{empId}', 'cms\CaseInformationController@employee')->name('employee');
                Route::get('/case-info/edit/{id}', 'cms\CaseInformationController@edit')->name('case-info-edit');
                Route::post('/case-info/edit/{id}', 'cms\CaseInformationController@updateCaseInformation')->name('updateCaseInformation');
                Route::post('/case-info', 'cms\CaseInformationController@storeCaseInformation')->name('storeCaseInformation');
                Route::post('/case-info-dataTable', 'cms\CaseInformationController@caseInformationDatatable')->name('caseInformationDatatable');
                Route::get('/case-info/download/{id}', 'cms\CaseInformationController@caseInformationDownload')->name('case-info-file-download');
                Route::get('/get-organization', 'cms\CaseInformationController@getOrganization')->name('get-organization');
                Route::get('/get-organization-data/{orgId}', 'cms\CaseInformationController@getOrganizationData')->name('get-organization-data');
                Route::get('/add-organization', 'cms\CaseInformationController@storeOrganization')->name('add-organization');
                Route::get('/get-emp-info', 'cms\CaseInformationController@getEmployeeData')->name('get-emp-info');
            });

            Route::group(['name' => 'rate-chart', 'as' => 'rate-chart.'], function () {
                Route::get('/rate-chart', 'cms\setup\RateChartController@index')->name('rate-chart-index');
                Route::get('/rate-chart/edit/{id}', 'cms\setup\RateChartController@edit')->name('rate-chart-edit');
                Route::post('/rate-chart/edit/{id}', 'cms\setup\RateChartController@updateRateChart')->name('updateRateChart');
                Route::post('/rate-chart', 'cms\setup\RateChartController@storeRateChart')->name('storeRateChart');
                Route::post('/rate-chart-dataTable', 'cms\setup\RateChartController@rateChartDatatable')->name('rateChartDatatable');
            });

            Route::group(['name' => 'lawyer-info', 'as' => 'lawyer-info.'], function () {
                Route::get('/lawyer-info', 'cms\LawyerInformationController@index')->name('lawyer-info-index');
                Route::get('/lawyer-info/edit/{id}', 'cms\LawyerInformationController@edit')->name('lawyer-info-edit');
                Route::post('/lawyer-info/edit/{id}', 'cms\LawyerInformationController@updateLawyerInformation')->name('updateLawyerInformation');
                Route::post('/lawyer-info', 'cms\LawyerInformationController@storeLawyerInformation')->name('storeLawyerInformation');
                Route::post('/lawyer-info-dataTable', 'cms\LawyerInformationController@lawyerInfoDatatable')->name('lawyerInfoDatatable');
                Route::get('/get-district-ajax', 'cms\LawyerInformationController@getDistrict')->name('getDistrict');
                Route::get('/get-thana-ajax', 'cms\LawyerInformationController@getThana')->name('getThana');
                Route::get('/get-branch-ajax', 'cms\LawyerInformationController@getBranch')->name('getBranch');
                Route::get('/get--lawyer-case-ajax', 'cms\LawyerInformationController@getIndividualLawyerCaseData')->name('get--lawyer-case-ajax');
            });

            Route::group(['name' => 'lawyer-assignment', 'as' => 'lawyer-assignment.'], function () {
                Route::get('/lawyer-assignment', 'cms\LawyerAssignmentController@index')->name('lawyer-assignment-index');
                Route::post('/lawyer-assignment-case-datatable', 'cms\LawyerAssignmentController@dataTable')->name('lawyer-assignment-case-datatable');
                Route::get('/lawyer-assignment/{id}', 'cms\LawyerAssignmentController@edit')->name('lawyer-assignment-edit');
                Route::post('/lawyer-assignment/{id}', 'cms\LawyerAssignmentController@storeLawyerAssignData')->name('storeLawyerAssignData');
            });

            Route::group(['name' => 'case-info-update', 'as' => 'case-info-update.'], function () {
                Route::get('/case-info-update', 'cms\CaseInformationUpdateController@index')->name('case-info-update-index');
                Route::get('/case-info-update/edit/{id}', 'cms\CaseInformationUpdateController@edit')->name('case-info-update-edit');
                Route::post('/case-info-update/edit/{id}', 'cms\CaseInformationUpdateController@updateCaseInfoUpdate')->name('updateCaseInfoUpdate');
                Route::post('/case-info-update', 'cms\CaseInformationUpdateController@storeCaseInfoUpdate')->name('storeCaseInfoUpdate');
                Route::get('/get-case-master-data', 'cms\CaseInformationUpdateController@getCaseMasterData')->name('getCaseMasterData');
                Route::get('/get-lawyer', 'cms\CaseInformationUpdateController@getLawyer')->name('getLawyer');
                Route::get('/get-case-comp-data', 'cms\CaseInformationUpdateController@getComplainantData')->name('getComplainantData');
                Route::get('/get-case-def-data', 'cms\CaseInformationUpdateController@getDefendentData')->name('getDefendentData');
                Route::post('/case-info-update-dataTable', 'cms\CaseInformationUpdateController@caseInfoUpdateDatatable')->name('caseInfoUpdateDatatable');
                Route::get('/get-case-data', 'cms\CaseInformationUpdateController@getAllCase')->name('getAllCase');
                Route::get('/case-update-doc-remove', 'cms\CaseInformationUpdateController@caseDocRemove')->name('caseDocRemove');
                Route::get('/case-info-update/download/{id}', 'cms\CaseInformationUpdateController@caseInformationUpdateDownload')->name('case-info-update-file-download');
            });

            Route::group(['name' => 'lawyer-bill-info', 'as' => 'lawyer-bill-info.'], function () {
                Route::get('/lawyer-bill-info', 'cms\LawyerBillController@index')->name('lawyer-bill-info-index');
                Route::post('/lawyer-bill-info-datatable', 'cms\LawyerBillController@dataTable')->name('lawyer-bill-info-datatable');
                Route::get('/lawyer-bill/{lawyer_id}', 'cms\LawyerBillController@getCase')->name('getCase');
                Route::get('/store-lawyer-bill-info', 'cms\LawyerBillController@storeLawyerBillInformation')->name('storeLawyerBillInformation');
                Route::get('/lawyer-bill-submit', 'cms\LawyerBillController@lawyerBillSubmit')->name('lawyer-bill-submit');
                Route::post('/bill-info-datatable', 'cms\LawyerBillController@billTable')->name('bill-info-datatable');
                Route::post('/save-bill', 'cms\LawyerBillController@saveBill')->name('save-bill');
                Route::post('/bill-voucher-datatable', 'cms\LawyerBillController@billVoucherTable')->name('bill-voucher-datatable');
            });

            Route::group(['name' => 'mobile-court-info', 'as' => 'mobile-court-info.'], function () {
                Route::get('/mobile-court', 'cms\MobileCourtController@index')->name('mobile-court-index');
                Route::get('/mobile-court/{id}', 'cms\MobileCourtController@edit')->name('mobile-court-edit');
                Route::put('/mobile-court/{id}', 'cms\MobileCourtController@update')->name('mobile-court-update');
                Route::post('/mobile-court-post', 'cms\MobileCourtController@post')->name('mobile-court-post');
                Route::post('/mobile-court-datatable', 'cms\MobileCourtController@dataTableList')->name('mobile-court-datatable');
                Route::get('/get-employee', 'cms\MobileCourtController@getEmp')->name('get-employee');
                Route::get('/add-convicted-law', 'cms\MobileCourtController@addConvictedLaw')->name('add-convicted-law');
                Route::get('/add-court-place', 'cms\MobileCourtController@addCourtPlace')->name('add-court-place');
                Route::get('/get-law', 'cms\MobileCourtController@getLaw')->name('get-law');
                Route::get('/get-court-place', 'cms\MobileCourtController@getCourtPlace')->name('get-court-place');
            });

            Route::group(['name' => 'report-generator', 'as' => 'report-generator.'], function () {
                Route::get('/report-generators', 'cms\ReportController@reportGenerator')->name('index');
                Route::get('/report-generator-params/{id}', 'cms\ReportController@reportParams')->name('report-params');
            });

            Route::group(['name' => 'dashboard-search', 'as' => 'lawyer-bill-info.'], function () {
                //Route::get('/dashboard-search', 'HomeController@index')->name('dashboard-search-index');
                Route::post('/dashboard-search-data', 'HomeController@dataTableList')->name('dashboard-search-data');
            });


        });

});

