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
Route::post("emurun-fd-tickets-missing","api\EmurunFDController@insertMissingTicket");
//jcn fd
Route::post("jcn-fd-groups","api\JCNFDController@getAllGroups");
Route::post("jcn-fd-companies","api\JCNFDController@getAllCompanies");
Route::post("jcn-fd-agents","api\JCNFDController@getAllAgents");
Route::post("jcn-fd-contacts","api\JCNFDController@getAllContacts");
Route::post("jcn-fd-tickets","api\JCNFDController@getAllTickets");
Route::post("jcn-fd-tickets-latests","api\JCNFDController@getLatestTicketExport");
Route::post("jcn-fd-tickets-missing","api\JCNFDController@insertMissingTicket");
Route::post("jcn-fd-tickets-duplicates","api\JCNFDController@duplicateData");
//cameron fd
Route::post("cameron-fd-groups","api\CameronFDController@getAllGroups");
Route::post("cameron-fd-companies","api\CameronFDController@getAllCompanies");
Route::post("cameron-fd-agents","api\CameronFDController@getAllAgents");
Route::post("cameron-fd-contacts","api\CameronFDController@getAllContacts");
Route::post("cameron-fd-tickets","api\CameronFDController@getAllTickets");
Route::post("cameron-fd-tickets-latests","api\CameronFDController@getLatestTicketExport");
//toureast fd
Route::post("toureast-fd-groups","api\ToureastFDController@getAllGroups");
Route::post("toureast-fd-companies","api\ToureastFDController@getAllCompanies");
Route::post("toureast-fd-agents","api\ToureastFDController@getAllAgents");
Route::post("toureast-fd-contacts","api\ToureastFDController@getAllContacts");
Route::post("toureast-fd-tickets","api\ToureastFDController@getAllTickets");
Route::post("toureast-fd-tickets-latests","api\ToureastFDController@getLatestTicketExport");
//raywhite fd
Route::post("raywhite-fd-groups","api\RaywhiteFDController@getAllGroups");
Route::post("raywhite-fd-companies","api\RaywhiteFDController@getAllCompanies");
Route::post("raywhite-fd-agents","api\RaywhiteFDController@getAllAgents");
Route::post("raywhite-fd-contacts","api\RaywhiteFDController@getAllContacts");
Route::post("raywhite-fd-tickets","api\RaywhiteFDController@getAllTickets");
Route::post("raywhite-fd-tickets-latests","api\RaywhiteFDController@getLatestTicketExport");
//JCN Finance fd
Route::post("jcn-finace-fd-groups","api\JCNFinanceFDController@getAllGroups");
Route::post("jcn-finace-fd-companies","api\JCNFinanceFDController@getAllCompanies");
Route::post("jcn-finace-fd-agents","api\JCNFinanceFDController@getAllAgents");
Route::post("jcn-finace-fd-contacts","api\JCNFinanceFDController@getAllContacts");
Route::post("jcn-finace-fd-tickets","api\JCNFinanceFDController@getAllTickets");
Route::post("jcn-finace-fd-tickets-latests","api\JCNFinanceFDController@getLatestTicketExport");

//Dixon fd
Route::post("dixon-fd-groups","api\DixonFDController@getAllGroups");
Route::post("dixon-fd-companies","api\DixonFDController@getAllCompanies");
Route::post("dixon-fd-agents","api\DixonFDController@getAllAgents");
Route::post("dixon-fd-contacts","api\DixonFDController@getAllContacts");
Route::post("dixon-fd-tickets","api\DixonFDController@getAllTickets");
Route::post("dixon-fd-tickets-latests","api\DixonFDController@getLatestTicketExport");

//RECD fd
Route::post("recd-fd-groups","api\RECDFDController@getAllGroups");
Route::post("recd-fd-companies","api\RECDFDController@getAllCompanies");
Route::post("recd-fd-agents","api\RECDFDController@getAllAgents");
Route::post("recd-fd-contacts","api\RECDFDController@getAllContacts");
Route::post("recd-fd-tickets","api\RECDFDController@getAllTickets");
Route::post("recd-fd-tickets-latests","api\RECDFDController@getLatestTicketExport");

//HBurgers fd
Route::post("hburgers-fd-groups","api\HBurgersController@getAllGroups");
Route::post("hburgers-fd-companies","api\HBurgersController@getAllCompanies");
Route::post("hburgers-fd-agents","api\HBurgersController@getAllAgents");
Route::post("hburgers-fd-contacts","api\HBurgersController@getAllContacts");
Route::post("hburgers-fd-tickets","api\HBurgersController@getAllTickets");
Route::post("hburgers-fd-tickets-latests","api\HBurgersController@getLatestTicketExport");

//sample
Route::post("test-insert", "SampleController@testMethod");