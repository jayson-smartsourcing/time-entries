<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("time-entries","api\TimeEntriesController@getTimeEntries");
Route::post("get-all-agents","api\TimeEntriesController@getAllAgents");
Route::post("get-time-entries-api","api\TimeEntriesController@getTimeEntriesApi");
Route::post("get-time-entries-latest","api\TimeEntriesController@getTimeEntriesThreeDaysAgo");
Route::get("test","api\TimeEntriesController@test");
//barry plant fs
Route::post("bp-ticket-export","api\TicketExportController@getAllTicketExport");
Route::post("bp-requester","api\TicketExportController@getAllRequester");
Route::post("bp-departments","api\TicketExportController@getAllDepartments");
Route::post("bp-groups","api\TicketExportController@getAllGroups");
Route::post("bp-latest-ticket-export","api\TicketExportController@getLatestTicketExport");
Route::get("bp-test","api\TicketExportController@test");
Route::post("bp-agents","api\TicketExportController@getAllAgents");
//harris fs
Route::post("hr-groups","api\HarrisFreshServiceController@getAllGroups");
Route::post("hr-departments","api\HarrisFreshServiceController@getAllDepartments");
Route::post("hr-requesters","api\HarrisFreshServiceController@getAllRequester");
Route::post("hr-agents","api\HarrisFreshServiceController@getAllAgents");
Route::post("hr-ticket-export","api\HarrisFreshServiceController@getAllTicketExport");
Route::post("hr-ticket-latest","api\HarrisFreshServiceController@getLatestTicketExport");
//jellis craig fs
Route::post("jc-fs-groups","api\JellisCraigFSController@getAllGroups");
Route::post("jc-fs-departments","api\JellisCraigFSController@getAllDepartments");
Route::post("jc-fs-requesters","api\JellisCraigFSController@getAllRequester");
Route::post("jc-fs-agents","api\JellisCraigFSController@getAllAgents");
Route::post("jc-fs-ticket","api\JellisCraigFSController@getAllTicketExport");
Route::post("jc-fs-ticket-latest","api\JellisCraigFSController@getLatestTicketExport");
