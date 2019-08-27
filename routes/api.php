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
//Route::post("bp-ticket-export","api\TicketExportController@getAllTicketExport");
Route::post("bp-requester","api\TicketExportController@getAllRequester");
Route::post("bp-departments","api\TicketExportController@getAllDepartments");
Route::post("bp-groups","api\TicketExportController@getAllGroups");
// Route::post("bp-latest-ticket-export","api\TicketExportController@getLatestTicketExport");
Route::get("bp-test","api\TicketExportController@test");
Route::post("bp-agents","api\TicketExportController@getAllAgents");
Route::post("bp-ticket-export","api\TicketExportController@getAllTicketExportV2");
Route::post("bp-latest-ticket-export","api\TicketExportController@getLatestTicketExportV2");

//harris fs
Route::post("hr-groups","api\HarrisFreshServiceController@getAllGroups");
Route::post("hr-departments","api\HarrisFreshServiceController@getAllDepartments");
Route::post("hr-requesters","api\HarrisFreshServiceController@getAllRequester");
Route::post("hr-agents","api\HarrisFreshServiceController@getAllAgents");
// Route::post("hr-ticket-export","api\HarrisFreshServiceController@getAllTicketExport");
// Route::post("hr-ticket-latest","api\HarrisFreshServiceController@getLatestTicketExport");
Route::post("hr-tickets","api\HarrisFreshServiceController@getAllTicketExportV2");
Route::post("hr-ticket-latest","api\HarrisFreshServiceController@getLatestTicketExportV2");

//jellis craig fs
Route::post("jc-fs-groups","api\JellisCraigFSController@getAllGroups");
Route::post("jc-fs-departments","api\JellisCraigFSController@getAllDepartments");
Route::post("jc-fs-requesters","api\JellisCraigFSController@getAllRequester");
Route::post("jc-fs-agents","api\JellisCraigFSController@getAllAgents");
// Route::post("jc-fs-ticket","api\JellisCraigFSController@getAllTicketExport");
// Route::post("jc-fs-ticket-latest","api\JellisCraigFSController@getLatestTicketExport");
Route::post("jc-fs-ticket","api\JellisCraigFSController@getAllTicketExportV2");
Route::post("jc-fs-ticket-latest","api\JellisCraigFSController@getLatestTicketExportV2");

//harris fd
//Route::post("hs-fd-ticket","api\HarrisSalesController@getAllTickets");
Route::post("hs-fd-groups","api\HarrisSalesController@getAllGroups");
Route::post("hs-fd-companies","api\HarrisSalesController@getAllCompanies");
Route::post("hs-fd-agents","api\HarrisSalesController@getAllAgents");
Route::post("hs-fd-contacts","api\HarrisSalesController@getAllContacts");
//Route::post("hs-fd-ticket-latest","api\HarrisSalesController@getLatestTicketExport");
Route::post("hs-fd-time-entries","api\HarrisSalesController@getAllTimeEntries");
Route::post("hs-fd-time-entries-latest","api\HarrisSalesController@getTimeEntriesThreeDaysAgo");
Route::post("hs-fd-ticket","api\HarrisSalesController@getAllTicketsV2");
Route::post("hs-fd-ticket-latest","api\HarrisSalesController@getLatestTicketExportV2");

//dingles fd
Route::post("dingles-fd-groups","api\DinglesFDController@getAllGroups");
Route::post("dingles-fd-companies","api\DinglesFDController@getAllCompanies");
Route::post("dingles-fd-agents","api\DinglesFDController@getAllAgents");
Route::post("dingles-fd-contacts","api\DinglesFDController@getAllContacts");
// Route::post("dingles-fd-tickets","api\DinglesFDController@getAllTickets");
//Route::post("dingles-fd-tickets-latest","api\DinglesFDController@getLatestTicketExport");
Route::post("dingles-fd-tickets","api\DinglesFDController@getAllTicketsV2");
Route::post("dingles-fd-tickets-latest","api\DinglesFDController@getLatestTicketExportV2");

//emurun fd
Route::post("emurun-fd-groups","api\EmurunFDController@getAllGroups");
Route::post("emurun-fd-companies","api\EmurunFDController@getAllCompanies");
Route::post("emurun-fd-agents","api\EmurunFDController@getAllAgents");
Route::post("emurun-fd-contacts","api\EmurunFDController@getAllContacts");
// Route::post("emurun-fd-tickets","api\EmurunFDController@getAllTickets");
// Route::post("emurun-fd-tickets-latests","api\EmurunFDController@getLatestTicketExport");
Route::post("emurun-fd-tickets-missing","api\EmurunFDController@insertMissingTicket");
Route::post("emurun-fd-tickets","api\EmurunFDController@getAllTicketsV2");
Route::post("emurun-fd-tickets-latests","api\EmurunFDController@getLatestTicketExportV2");


//jcn fd
Route::post("jcn-fd-groups","api\JCNFDController@getAllGroups");
Route::post("jcn-fd-companies","api\JCNFDController@getAllCompanies");
Route::post("jcn-fd-agents","api\JCNFDController@getAllAgents");
Route::post("jcn-fd-contacts","api\JCNFDController@getAllContacts");
// Route::post("jcn-fd-tickets","api\JCNFDController@getAllTickets");
// Route::post("jcn-fd-tickets-latests","api\JCNFDController@getLatestTicketExport");
Route::post("jcn-fd-tickets-missing","api\JCNFDController@insertMissingTicket");
Route::post("jcn-fd-tickets-duplicates","api\JCNFDController@duplicateData");
Route::post("jcn-fd-tickets","api\JCNFDController@getAllTicketsV2");
Route::post("jcn-fd-tickets-latests","api\JCNFDController@getLatestTicketExportV2");

//cameron fd
Route::post("cameron-fd-groups","api\CameronFDController@getAllGroups");
Route::post("cameron-fd-companies","api\CameronFDController@getAllCompanies");
Route::post("cameron-fd-agents","api\CameronFDController@getAllAgents");
Route::post("cameron-fd-contacts","api\CameronFDController@getAllContacts");
//Route::post("cameron-fd-tickets","api\CameronFDController@getAllTickets")
//Route::post("cameron-fd-tickets-latests","api\CameronFDController@getLatestTicketExport");
Route::post("cameron-fd-tickets","api\CameronFDController@getAllTicketsV2");
Route::post("cameron-fd-tickets-latests","api\CameronFDController@getLatestTicketExportV2");

//toureast fd
Route::post("toureast-fd-groups","api\ToureastFDController@getAllGroups");
Route::post("toureast-fd-companies","api\ToureastFDController@getAllCompanies");
Route::post("toureast-fd-agents","api\ToureastFDController@getAllAgents");
Route::post("toureast-fd-contacts","api\ToureastFDController@getAllContacts");
//Route::post("toureast-fd-tickets","api\ToureastFDController@getAllTickets");
//Route::post("toureast-fd-tickets-latests","api\ToureastFDController@getLatestTicketExport");
Route::post("toureast-fd-tickets","api\ToureastFDController@getAllTicketsV2");
Route::post("toureast-fd-tickets-latests","api\ToureastFDController@getLatestTicketExportV2");
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
// Route::post("jcn-finace-fd-tickets","api\JCNFinanceFDController@getAllTickets");
// Route::post("jcn-finace-fd-tickets-latests","api\JCNFinanceFDController@getLatestTicketExport");
Route::post("jcn-finace-fd-tickets","api\JCNFinanceFDController@getAllTicketsV2");
Route::post("jcn-finace-fd-tickets-latests","api\JCNFinanceFDController@getLatestTicketExportV2");

//Dixon fd
Route::post("dixon-fd-groups","api\DixonFDController@getAllGroups");
Route::post("dixon-fd-companies","api\DixonFDController@getAllCompanies");
Route::post("dixon-fd-agents","api\DixonFDController@getAllAgents");
Route::post("dixon-fd-contacts","api\DixonFDController@getAllContacts");
//Route::post("dixon-fd-tickets","api\DixonFDController@getAllTickets");
//Route::post("dixon-fd-tickets-latests","api\DixonFDController@getLatestTicketExport");
Route::post("dixon-fd-tickets","api\DixonFDController@getAllTicketsV2");
Route::post("dixon-fd-tickets-latests","api\DixonFDController@getLatestTicketExportV2");

//RECD fd
Route::post("recd-fd-groups","api\RECDFDController@getAllGroups");
Route::post("recd-fd-companies","api\RECDFDController@getAllCompanies");
Route::post("recd-fd-agents","api\RECDFDController@getAllAgents");
Route::post("recd-fd-contacts","api\RECDFDController@getAllContacts");
//Route::post("recd-fd-tickets","api\RECDFDController@getAllTickets");
//Route::post("recd-fd-tickets-latests","api\RECDFDController@getLatestTicketExport");
Route::post("recd-fd-tickets","api\RECDFDController@getAllTicketsV2");
Route::post("recd-fd-tickets-latests","api\RECDFDController@getLatestTicketExportV2");

//HBurgers fd
Route::post("hburgers-fd-groups","api\HBurgersController@getAllGroups");
Route::post("hburgers-fd-companies","api\HBurgersController@getAllCompanies");
Route::post("hburgers-fd-agents","api\HBurgersController@getAllAgents");
Route::post("hburgers-fd-contacts","api\HBurgersController@getAllContacts");
//Route::post("hburgers-fd-tickets","api\HBurgersController@getAllTickets");
//Route::post("hburgers-fd-tickets-latests","api\HBurgersController@getLatestTicketExport");
Route::post("hburgers-fd-tickets","api\HBurgersController@getAllTicketsV2");
Route::post("hburgers-fd-tickets-latests","api\HBurgersController@getLatestTicketExportV2");

//JCNE fd
Route::post("jcne-fd-groups","api\JCNEFDController@getAllGroups");
Route::post("jcne-fd-companies","api\JCNEFDController@getAllCompanies");
Route::post("jcne-fd-agents","api\JCNEFDController@getAllAgents");
Route::post("jcne-fd-contacts","api\JCNEFDController@getAllContacts");
//Route::post("jcne-fd-tickets","api\JCNEFDController@getAllTickets");
//Route::post("jcne-fd-tickets-latests","api\JCNEFDController@getLatestTicketExport");
Route::post("jcne-fd-tickets","api\JCNEFDController@getAllTicketsV2");
Route::post("jcne-fd-tickets-latests","api\JCNEFDController@getLatestTicketExportV2");

//JCS fd
Route::post("jcs-fd-groups","api\JCSFDController@getAllGroups");
Route::post("jcs-fd-companies","api\JCSFDController@getAllCompanies");
Route::post("jcs-fd-agents","api\JCSFDController@getAllAgents");
Route::post("jcs-fd-contacts","api\JCSFDController@getAllContacts");
//Route::post("jcs-fd-tickets","api\JCSFDController@getAllTickets");
//Route::post("jcs-fd-tickets-latests","api\JCSFDController@getLatestTicketExport");
Route::post("jcs-fd-tickets","api\JCSFDController@getAllTicketsV2");
Route::post("jcs-fd-tickets-latests","api\JCSFDController@getLatestTicketExportV2");

//JG fd
Route::post("jg-fd-groups","api\JGFDController@getAllGroups");
Route::post("jg-fd-companies","api\JGFDController@getAllCompanies");
Route::post("jg-fd-agents","api\JGFDController@getAllAgents");
Route::post("jg-fd-contacts","api\JGFDController@getAllContacts");
//Route::post("jg-fd-tickets","api\JGFDController@getAllTickets");
//Route::post("jg-fd-tickets-latests","api\JGFDController@getLatestTicketExport");
Route::post("jg-fd-tickets","api\JGFDController@getAllTicketsV2");
Route::post("jg-fd-tickets-latests","api\JGFDController@getLatestTicketExportV2");


//employee Ratings live
Route::post("insert-rating", "api\EmployeeSatisfactoryController@addEmployeeRatings");
Route::get("email-rating", "api\EmployeeSatisfactoryController@emailToEmployee");
Route::get("check-rating/{hash}", "api\EmployeeSatisfactoryController@checkCurrentMonthRate");
Route::get("insert-rating/import", "api\EmployeeSatisfactoryController@importFromCSV");



//user authentication
Route::post('login', 'api\userController@login');

Route::group(['middleware' => 'auth:api'], function(){
	Route::get('check/token/a', 'api\userController@checkToken');
});

//JG fs©
Route::post("jcb-fs-groups","api\JCBFSController@getAllGroups");
Route::post("jcb-fs-departments","api\JCBFSController@getAllDepartments");
Route::post("jcb-fs-requesters","api\JCBFSController@getAllRequester");
Route::post("jcb-fs-agents","api\JCBFSController@getAllAgents");
// Route::post("jcb-fs-tickets","api\JCBFSController@getAllTicketExport");
// Route::post("jcb-fs-tickets-latests","api\JCBFSController@getLatestTicketExport");
Route::post("jcb-fs-tickets","api\JCBFSController@getAllTicketExportV2");
Route::post("jcb-fs-tickets-latests","api\JCBFSController@getLatestTicketExportV2");


//insert Data
Route::get("insert-resume-data","api\ImportBossJobToDBController@insertData");

Route::post("parse-json","api\XmlParserController@parseToJson");

//LJ Hooker
Route::post("lj-hooker-fd-groups","api\LJHookerFDController@getAllGroups");
Route::post("lj-hooker-fd-companies","api\LJHookerFDController@getAllCompanies");
Route::post("lj-hooker-fd-agents","api\LJHookerFDController@getAllAgents");
Route::post("lj-hooker-fd-contacts","api\LJHookerFDController@getAllContacts");
Route::post("lj-hooker-fd-tickets","api\LJHookerFDController@getAllTicketsV2");
Route::post("lj-hooker-fd-tickets-latests","api\LJHookerFDController@getLatestTicketExportV2");

Route::post("avnu-fd-groups","api\AvnuFDController@getAllGroups");
Route::post("avnu-fd-companies","api\AvnuFDController@getAllCompanies");
Route::post("avnu-fd-agents","api\AvnuFDController@getAllAgents");
Route::post("avnu-fd-contacts","api\AvnuFDController@getAllContacts");
Route::post("avnu-fd-tickets","api\AvnuFDController@getAllTicketsV2");
Route::post("avnu-fd-tickets-latests","api\AvnuFDController@getLatestTicketExportV2");

