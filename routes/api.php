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
//harris fd
Route::post("hs-fd-ticket","api\HarrisSalesController@getAllTickets");
Route::post("hs-fd-groups","api\HarrisSalesController@getAllGroups");
Route::post("hs-fd-companies","api\HarrisSalesController@getAllCompanies");
Route::post("hs-fd-agents","api\HarrisSalesController@getAllAgents");
Route::post("hs-fd-contacts","api\HarrisSalesController@getAllContacts");
Route::post("hs-fd-ticket-latest","api\HarrisSalesController@getLatestTicketExport");
Route::post("hs-fd-time-entries","api\HarrisSalesController@getAllTimeEntries");
Route::post("hs-fd-time-entries-latest","api\HarrisSalesController@getTimeEntriesThreeDaysAgo");
//dingles fd
Route::post("dingles-fd-groups","api\DinglesFDController@getAllGroups");
Route::post("dingles-fd-companies","api\DinglesFDController@getAllCompanies");
Route::post("dingles-fd-agents","api\DinglesFDController@getAllAgents");
Route::post("dingles-fd-contacts","api\DinglesFDController@getAllContacts");
Route::post("dingles-fd-tickets","api\DinglesFDController@getAllTickets");
Route::post("dingles-fd-tickets-latest","api\DinglesFDController@getLatestTicketExport");
//emurun fd
Route::post("emurun-fd-groups","api\EmurunFDController@getAllGroups");
Route::post("emurun-fd-companies","api\EmurunFDController@getAllCompanies");
Route::post("emurun-fd-agents","api\EmurunFDController@getAllAgents");
Route::post("emurun-fd-contacts","api\EmurunFDController@getAllContacts");
Route::post("emurun-fd-tickets","api\EmurunFDController@getAllTickets");
Route::post("emurun-fd-tickets-latests","api\EmurunFDController@getLatestTicketExport");
