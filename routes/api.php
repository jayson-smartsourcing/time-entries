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
// Route::post("bp-latest-ticket-export","api\TicketExportController@getLatestTicketExportV2");
Route::get("bp-latest-ticket-export","api\TicketExportController@getLatestTicketExportV2");
Route::get("bp-update-all","api\TicketExportController@updateAll");

//harris fs
Route::post("hr-groups","api\HarrisFreshServiceController@getAllGroups");
Route::post("hr-departments","api\HarrisFreshServiceController@getAllDepartments");
Route::post("hr-requesters","api\HarrisFreshServiceController@getAllRequester");
Route::post("hr-agents","api\HarrisFreshServiceController@getAllAgents");
// Route::post("hr-ticket-export","api\HarrisFreshServiceController@getAllTicketExport");
// Route::post("hr-ticket-latest","api\HarrisFreshServiceController@getLatestTicketExport");
Route::post("hr-tickets","api\HarrisFreshServiceController@getAllTicketExportV2");
// Route::post("hr-ticket-latest","api\HarrisFreshServiceController@getLatestTicketExportV2");
Route::get("hr-ticket-latest","api\HarrisFreshServiceController@getLatestTicketExportV2");    
Route::get("hr-update-all","api\HarrisFreshServiceController@updateAll");

//jellis craig fs
Route::post("jc-fs-groups","api\JellisCraigFSController@getAllGroups");
Route::post("jc-fs-departments","api\JellisCraigFSController@getAllDepartments");
Route::post("jc-fs-requesters","api\JellisCraigFSController@getAllRequester");
Route::post("jc-fs-agents","api\JellisCraigFSController@getAllAgents");
// Route::post("jc-fs-ticket","api\JellisCraigFSController@getAllTicketExport");
// Route::post("jc-fs-ticket-latest","api\JellisCraigFSController@getLatestTicketExport");
Route::post("jc-fs-ticket","api\JellisCraigFSController@getAllTicketExportV2");
// Route::post("jc-fs-ticket-latest","api\JellisCraigFSController@getLatestTicketExportV2");
Route::get("jc-fs-ticket-latest","api\JellisCraigFSController@getLatestTicketExportV2");
Route::get("jc-fs-update-all","api\JellisCraigFSController@updateAll");

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
// Route::post("hs-fd-ticket-latest","api\HarrisSalesController@getLatestTicketExportV2");
Route::get("hs-fd-ticket-latest","api\HarrisSalesController@getLatestTicketExportV2");
Route::get("hs-fd-update-all","api\HarrisSalesController@updateAll");

//dingles fd
Route::post("dingles-fd-groups","api\DinglesFDController@getAllGroups");
Route::post("dingles-fd-companies","api\DinglesFDController@getAllCompanies");
Route::post("dingles-fd-agents","api\DinglesFDController@getAllAgents");
Route::post("dingles-fd-contacts","api\DinglesFDController@getAllContacts");
// Route::post("dingles-fd-tickets","api\DinglesFDController@getAllTickets");
//Route::post("dingles-fd-tickets-latest","api\DinglesFDController@getLatestTicketExport");
Route::post("dingles-fd-tickets","api\DinglesFDController@getAllTicketsV2");
// Route::post("dingles-fd-tickets-latest","api\DinglesFDController@getLatestTicketExportV2");
Route::get("dingles-fd-tickets-latest","api\DinglesFDController@getLatestTicketExportV2");
Route::get("dingles-fd-update-all","api\DinglesFDController@updateAll");

//emurun fd
Route::post("emurun-fd-groups","api\EmurunFDController@getAllGroups");
Route::post("emurun-fd-companies","api\EmurunFDController@getAllCompanies");
Route::post("emurun-fd-agents","api\EmurunFDController@getAllAgents");
Route::post("emurun-fd-contacts","api\EmurunFDController@getAllContacts");
// Route::post("emurun-fd-tickets","api\EmurunFDController@getAllTickets");
// Route::post("emurun-fd-tickets-latests","api\EmurunFDController@getLatestTicketExport");
Route::post("emurun-fd-tickets-missing","api\EmurunFDController@insertMissingTicket");
Route::post("emurun-fd-tickets","api\EmurunFDController@getAllTicketsV2");
// Route::post("emurun-fd-tickets-latests","api\EmurunFDController@getLatestTicketExportV2");
Route::get("emurun-fd-tickets-latests","api\EmurunFDController@getLatestTicketExportV2");
Route::get("emurun-fd-update-all","api\EmurunFDController@updateAll");

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
// Route::post("jcn-fd-tickets-latests","api\JCNFDController@getLatestTicketExportV2");
Route::get("jcn-fd-tickets-latests","api\JCNFDController@getLatestTicketExportV2");
Route::get("jcn-fd-update-all","api\JCNFDController@updateAll");
Route::get("jcn-fd-missing-requester","api\JCNFDController@missingRequester");

//cameron fd
Route::post("cameron-fd-groups","api\CameronFDController@getAllGroups");
Route::post("cameron-fd-companies","api\CameronFDController@getAllCompanies");
Route::post("cameron-fd-agents","api\CameronFDController@getAllAgents");
Route::post("cameron-fd-contacts","api\CameronFDController@getAllContacts");
//Route::post("cameron-fd-tickets","api\CameronFDController@getAllTickets")
//Route::post("cameron-fd-tickets-latests","api\CameronFDController@getLatestTicketExport");
Route::post("cameron-fd-tickets","api\CameronFDController@getAllTicketsV2");
// Route::post("cameron-fd-tickets-latests","api\CameronFDController@getLatestTicketExportV2");
Route::get("cameron-fd-tickets-latests","api\CameronFDController@getLatestTicketExportV2");
Route::get("cameron-fd-update-all","api\CameronFDController@updateAll");

//toureast fd
Route::post("toureast-fd-groups","api\ToureastFDController@getAllGroups");
Route::post("toureast-fd-companies","api\ToureastFDController@getAllCompanies");
Route::post("toureast-fd-agents","api\ToureastFDController@getAllAgents");
Route::post("toureast-fd-contacts","api\ToureastFDController@getAllContacts");
//Route::post("toureast-fd-tickets","api\ToureastFDController@getAllTickets");
//Route::post("toureast-fd-tickets-latests","api\ToureastFDController@getLatestTicketExport");
Route::post("toureast-fd-tickets","api\ToureastFDController@getAllTicketsV2");
// Route::post("toureast-fd-tickets-latests","api\ToureastFDController@getLatestTicketExportV2");
Route::get("toureast-fd-tickets-latests","api\ToureastFDController@getLatestTicketExportV2");
Route::get("toureast-fd-update-all","api\ToureastFDController@updateAll");

//raywhite fd
Route::post("raywhite-fd-groups","api\RaywhiteFDController@getAllGroups");
Route::post("raywhite-fd-companies","api\RaywhiteFDController@getAllCompanies");
Route::post("raywhite-fd-agents","api\RaywhiteFDController@getAllAgents");
Route::post("raywhite-fd-contacts","api\RaywhiteFDController@getAllContacts");
Route::post("raywhite-fd-tickets","api\RaywhiteFDController@getAllTickets");
// Route::post("raywhite-fd-tickets-latests","api\RaywhiteFDController@getLatestTicketExport");
Route::get("raywhite-fd-tickets-latests","api\RaywhiteFDController@getLatestTicketExport");
Route::get("raywhite-fd-update-all","api\RaywhiteFDController@updateAll");

//JCN Finance fd
Route::post("jcn-finace-fd-groups","api\JCNFinanceFDController@getAllGroups");
Route::post("jcn-finace-fd-companies","api\JCNFinanceFDController@getAllCompanies");
Route::post("jcn-finace-fd-agents","api\JCNFinanceFDController@getAllAgents");
Route::post("jcn-finace-fd-contacts","api\JCNFinanceFDController@getAllContacts");
// Route::post("jcn-finace-fd-tickets","api\JCNFinanceFDController@getAllTickets");
// Route::post("jcn-finace-fd-tickets-latests","api\JCNFinanceFDController@getLatestTicketExport");
Route::post("jcn-finace-fd-tickets","api\JCNFinanceFDController@getAllTicketsV2");
// Route::post("jcn-finace-fd-tickets-latests","api\JCNFinanceFDController@getLatestTicketExportV2");
Route::get("jcn-finace-fd-tickets-latests","api\JCNFinanceFDController@getLatestTicketExportV2");
Route::get("jcn-finace-fd-update-all","api\JCNFinanceFDController@updateAll");

//Dixon fd
Route::post("dixon-fd-groups","api\DixonFDController@getAllGroups");
Route::post("dixon-fd-companies","api\DixonFDController@getAllCompanies");
Route::post("dixon-fd-agents","api\DixonFDController@getAllAgents");
Route::post("dixon-fd-contacts","api\DixonFDController@getAllContacts");
//Route::post("dixon-fd-tickets","api\DixonFDController@getAllTickets");
//Route::post("dixon-fd-tickets-latests","api\DixonFDController@getLatestTicketExport");
Route::post("dixon-fd-tickets","api\DixonFDController@getAllTicketsV2");
// Route::post("dixon-fd-tickets-latests","api\DixonFDController@getLatestTicketExportV2");
Route::get("dixon-fd-tickets-latests","api\DixonFDController@getLatestTicketExportV2");
Route::get("dixon-fd-update-all","api\DixonFDController@updateAll");

//RECD fd
Route::post("recd-fd-groups","api\RECDFDController@getAllGroups");
Route::post("recd-fd-companies","api\RECDFDController@getAllCompanies");
Route::post("recd-fd-agents","api\RECDFDController@getAllAgents");
Route::post("recd-fd-contacts","api\RECDFDController@getAllContacts");
//Route::post("recd-fd-tickets","api\RECDFDController@getAllTickets");
//Route::post("recd-fd-tickets-latests","api\RECDFDController@getLatestTicketExport");
Route::post("recd-fd-tickets","api\RECDFDController@getAllTicketsV2");
// Route::post("recd-fd-tickets-latests","api\RECDFDController@getLatestTicketExportV2");
Route::get("recd-fd-tickets-latests","api\RECDFDController@getLatestTicketExportV2");
Route::get("recd-fd-update-all","api\RECDFDController@updateAll");

//HBurgers fd
Route::post("hburgers-fd-groups","api\HBurgersController@getAllGroups");
Route::post("hburgers-fd-companies","api\HBurgersController@getAllCompanies");
Route::post("hburgers-fd-agents","api\HBurgersController@getAllAgents");
Route::post("hburgers-fd-contacts","api\HBurgersController@getAllContacts");
//Route::post("hburgers-fd-tickets","api\HBurgersController@getAllTickets");
//Route::post("hburgers-fd-tickets-latests","api\HBurgersController@getLatestTicketExport");
Route::post("hburgers-fd-tickets","api\HBurgersController@getAllTicketsV2");
// Route::post("hburgers-fd-tickets-latests","api\HBurgersController@getLatestTicketExportV2");
Route::get("hburgers-fd-tickets-latests","api\HBurgersController@getLatestTicketExportV2");
Route::get("hburgers-fd-time-entry","api\HBurgersController@getLatestTimeEntryV2");
Route::get("hburgers-fd-update-all","api\HBurgersController@updateAll");

//JCNE fd
Route::post("jcne-fd-groups","api\JCNEFDController@getAllGroups");
Route::post("jcne-fd-companies","api\JCNEFDController@getAllCompanies");
Route::post("jcne-fd-agents","api\JCNEFDController@getAllAgents");
Route::post("jcne-fd-contacts","api\JCNEFDController@getAllContacts");
//Route::post("jcne-fd-tickets","api\JCNEFDController@getAllTickets");
//Route::post("jcne-fd-tickets-latests","api\JCNEFDController@getLatestTicketExport");
Route::post("jcne-fd-tickets","api\JCNEFDController@getAllTicketsV2");
// Route::post("jcne-fd-tickets-latests","api\JCNEFDController@getLatestTicketExportV2");
Route::get("jcne-fd-tickets-latests","api\JCNEFDController@getLatestTicketExportV2");
Route::get("jcne-fd-update-all","api\JCNEFDController@updateAll");

//JCS fd
Route::post("jcs-fd-groups","api\JCSFDController@getAllGroups");
Route::post("jcs-fd-companies","api\JCSFDController@getAllCompanies");
Route::post("jcs-fd-agents","api\JCSFDController@getAllAgents");
Route::post("jcs-fd-contacts","api\JCSFDController@getAllContacts");
//Route::post("jcs-fd-tickets","api\JCSFDController@getAllTickets");
//Route::post("jcs-fd-tickets-latests","api\JCSFDController@getLatestTicketExport");
Route::post("jcs-fd-tickets","api\JCSFDController@getAllTicketsV2");
// Route::post("jcs-fd-tickets-latests","api\JCSFDController@getLatestTicketExportV2");
Route::get("jcs-fd-tickets-latests","api\JCSFDController@getLatestTicketExportV2");
Route::get("jcs-fd-update-all","api\JCSFDController@updateAll");

//JG fd
Route::post("jg-fd-groups","api\JGFDController@getAllGroups");
Route::post("jg-fd-companies","api\JGFDController@getAllCompanies");
Route::post("jg-fd-agents","api\JGFDController@getAllAgents");
Route::post("jg-fd-contacts","api\JGFDController@getAllContacts");
//Route::post("jg-fd-tickets","api\JGFDController@getAllTickets");
//Route::post("jg-fd-tickets-latests","api\JGFDController@getLatestTicketExport");
Route::post("jg-fd-tickets","api\JGFDController@getAllTicketsV2");
// Route::post("jg-fd-tickets-latests","api\JGFDController@getLatestTicketExportV2");
Route::get("jg-fd-tickets-latests","api\JGFDController@getLatestTicketExportV2");
Route::get("jg-fd-update-all","api\JGFDController@updateAll");


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
// Route::post("jcb-fs-tickets-latests","api\JCBFSController@getLatestTicketExportV2");
Route::get("jcb-fs-tickets-latests","api\JCBFSController@getLatestTicketExportV2");
Route::get("jcb-fs-update-all","api\JCBFSController@updateAll");

//insert Data
Route::get("insert-resume-data","api\ImportBossJobToDBController@insertData");

Route::post("parse-json","api\XmlParserController@parseToJson");

//LJ Hooker
Route::post("lj-hooker-fd-groups","api\LJHookerFDController@getAllGroups");
Route::post("lj-hooker-fd-companies","api\LJHookerFDController@getAllCompanies");
Route::post("lj-hooker-fd-agents","api\LJHookerFDController@getAllAgents");
Route::post("lj-hooker-fd-contacts","api\LJHookerFDController@getAllContacts");
Route::post("lj-hooker-fd-tickets","api\LJHookerFDController@getAllTicketsV2");
// Route::post("lj-hooker-fd-tickets-latests","api\LJHookerFDController@getLatestTicketExportV2");
Route::get("lj-hooker-fd-tickets-latests","api\LJHookerFDController@getLatestTicketExportV2");
Route::get("lj-hooker-fd-update-all","api\LJHookerFDController@updateAll");

Route::post("avnu-fd-groups","api\AvnuFDController@getAllGroups");
Route::post("avnu-fd-companies","api\AvnuFDController@getAllCompanies");
Route::post("avnu-fd-agents","api\AvnuFDController@getAllAgents");
Route::post("avnu-fd-contacts","api\AvnuFDController@getAllContacts");
Route::post("avnu-fd-tickets","api\AvnuFDController@getAllTicketsV2");
// Route::post("avnu-fd-tickets-latests","api\AvnuFDController@getLatestTicketExportV2");
Route::get("avnu-fd-tickets-latests","api\AvnuFDController@getLatestTicketExportV2");
Route::get("avnu-fd-update-all","api\AvnuFDController@updateAll");

Route::post("tagflix-fd-groups","api\TagflixController@getAllGroups");
Route::post("tagflix-fd-companies","api\TagflixController@getAllCompanies");
Route::post("tagflix-fd-agents","api\TagflixController@getAllAgents");
Route::post("tagflix-fd-contacts","api\TagflixController@getAllContacts");
Route::post("tagflix-fd-tickets","api\TagflixController@getAllTicketsV2");
// Route::post("tagflix-fd-tickets-latests","api\TagflixController@getLatestTicketExportV2");
Route::get("tagflix-fd-tickets-latests","api\TagflixController@getLatestTicketExportV2");
Route::get("tagflix-fd-update-all","api\TagflixController@updateAll");


Route::get("lh-fd-groups","api\LHFDController@getAllGroups");
Route::post("lh-fd-companies","api\LHFDController@getAllCompanies");
Route::post("lh-fd-agents","api\LHFDController@getAllAgents");
Route::post("lh-fd-contacts","api\LHFDController@getAllContacts");
Route::post("lh-fd-tickets","api\LHFDController@getAllTicketsV2");
// Route::post("lh-fd-tickets-latests","api\LHFDController@getLatestTicketExportV2");
Route::get("lh-fd-tickets-latests","api\LHFDController@getLatestTicketExportV2");
Route::get("lh-fd-update-all","api\LHFDController@updateAll");

//eg
Route::get("eg-fd-survey","api\EGFDController@getAllSurvey");
Route::get("eg-fd-rating","api\EGFDController@getAllRating");
Route::get("eg-fd-rating-latest","api\EGFDController@getLatestRating");
Route::post("eg-fd-groups","api\EGFDController@getAllGroups");
Route::post("eg-fd-companies","api\EGFDController@getAllCompanies");
Route::post("eg-fd-agents","api\EGFDController@getAllAgents");
Route::post("eg-fd-contacts","api\EGFDController@getAllContacts");
Route::post("eg-fd-tickets","api\EGFDController@getAllTicketsV2");
Route::get("eg-fd-tickets-latest","api\EGFDController@getLatestTicketExportV2");
Route::get("eg-fd-update-all","api\EGFDController@updateAll");


//ds fd
Route::post("ds-fd-groups","api\DSFDController@getAllGroups");
Route::post("ds-fd-companies","api\DSFDController@getAllCompanies");
Route::post("ds-fd-agents","api\DSFDController@getAllAgents");
Route::post("ds-fd-contacts","api\DSFDController@getAllContacts");
// // Route::post("dingles-fd-tickets","api\DinglesFDController@getAllTickets");
// //Route::post("dingles-fd-tickets-latest","api\DinglesFDController@getLatestTicketExport");
Route::post("ds-fd-tickets","api\DSFDController@getAllTicketsV2");
// Route::post("ds-fd-tickets-latest","api\DSFDController@getLatestTicketExportV2");
Route::get("ds-fd-tickets-latest","api\DSFDController@getLatestTicketExportV2");
Route::get("ds-fd-update-all","api\DSFDController@updateAll");


//jcd fd
Route::post("jcd-fd-groups","api\JCDFDController@getAllGroups");
Route::post("jcd-fd-companies","api\JCDFDController@getAllCompanies");
Route::post("jcd-fd-agents","api\JCDFDController@getAllAgents");
Route::post("jcd-fd-contacts","api\JCDFDController@getAllContacts");
// // // Route::post("dingles-fd-tickets","api\DinglesFDController@getAllTickets");
// // //Route::post("dingles-fd-tickets-latest","api\DinglesFDController@getLatestTicketExport");
Route::post("jcd-fd-tickets","api\JCDFDController@getAllTicketsV2");
// Route::post("jcd-fd-tickets-latest","api\JCDFDController@getLatestTicketExportV2");
Route::get("jcd-fd-tickets-latest","api\JCDFDController@getLatestTicketExportV2");
Route::get("jcd-fd-update-all","api\JCDFDController@updateAll");


//estore fd
Route::post("estore-fd-groups","api\EstoreFDController@getAllGroups");
Route::post("estore-fd-companies","api\EstoreFDController@getAllCompanies");
Route::post("estore-fd-agents","api\EstoreFDController@getAllAgents");
Route::post("estore-fd-contacts","api\EstoreFDController@getAllContacts");
Route::post("estore-fd-tickets","api\EstoreFDController@getAllTicketsV2");
Route::get("estore-fd-tickets-latest","api\EstoreFDController@getLatestTicketExportV2");
Route::get("estore-fd-update-all","api\EstoreFDController@updateAll");

//urban angles fd
Route::post("urban-angles-fd-groups","api\UrbanAnglesFDController@getAllGroups");
Route::post("urban-angles-fd-companies","api\UrbanAnglesFDController@getAllCompanies");
Route::post("urban-angles-fd-agents","api\UrbanAnglesFDController@getAllAgents");
Route::post("urban-angles-contacts","api\UrbanAnglesFDController@getAllContacts");
Route::post("urban-angles-fd-tickets","api\UrbanAnglesFDController@getAllTicketsV2");
Route::get("urban-angles-fd-tickets-latest","api\UrbanAnglesFDController@getLatestTicketExportV2");
Route::get("urban-angles-fd-update-all","api\UrbanAnglesFDController@updateAll");

//ticket refresh
Route::get('/refresh-tickets/{token}', "InsertTimeEntriesController@refreshTicketExport");


//white label fd
Route::post("white-label-fd-groups","api\WhiteLabelFDController@getAllGroups");
Route::post("white-label-fd-companies","api\WhiteLabelFDController@getAllCompanies");
Route::post("white-label-fd-agents","api\WhiteLabelFDController@getAllAgents");
Route::post("white-label-contacts","api\WhiteLabelFDController@getAllContacts");
Route::post("white-label-fd-tickets","api\WhiteLabelFDController@getAllTicketsV2");
Route::get("white-label-fd-tickets-latest","api\WhiteLabelFDController@getLatestTicketExportV2");
Route::get("white-label-fd-update-all","api\WhiteLabelFDController@updateAll");

//ckb fd
Route::post("ckb-fd-groups","api\CKBFDController@getAllGroups");
Route::post("ckb-fd-companies","api\CKBFDController@getAllCompanies");
Route::post("ckb-fd-agents","api\CKBFDController@getAllAgents");
Route::post("ckb-contacts","api\CKBFDController@getAllContacts");
Route::post("ckb-fd-tickets","api\CKBFDController@getAllTicketsV2");
Route::get("ckb-fd-tickets-latest","api\CKBFDController@getLatestTicketExportV2");
Route::get("ckb-fd-update-all","api\CKBFDController@updateAll");


//activtrak logs and csv import
Route::post("import/logs", "api\ActivtrakController@importATlogs");
Route::post("update/emp_ref", "api\ActivtrakController@updateEmpRef");
Route::post('import/logs/csv',"api\ActivtrakController@importATlogsCSV");
Route::post('import/logs/csv/update',"api\ActivtrakController@updateAttendanceID");
Route::post('import/logs/csv/delete/{user}/{currdate}',"api\ActivtrakController@deleteLog");

//trend tile fd
Route::post("trendtile-fd-groups","api\TrendTileFDController@getAllGroups");
Route::post("trendtile-fd-companies","api\TrendTileFDController@getAllCompanies");
Route::post("trendtile-fd-agents","api\TrendTileFDController@getAllAgents");
Route::post("trendtile-fd-contacts","api\TrendTileFDController@getAllContacts");
Route::post("trendtile-fd-tickets","api\TrendTileFDController@getAllTicketsV2");
Route::get("trendtile-fd-tickets-latest","api\TrendTileFDController@getLatestTicketExportV2");
Route::get("trendtile-fd-update-all","api\TrendTileFDController@updateAll");

//collective wellness fd
Route::post("cw-fd-groups","api\CWFDController@getAllGroups");
Route::post("cw-fd-companies","api\CWFDController@getAllCompanies");
Route::post("cw-fd-agents","api\CWFDController@getAllAgents");
Route::post("cw-fd-contacts","api\CWFDController@getAllContacts");
Route::post("cw-fd-tickets","api\CWFDController@getAllTicketsV2");
Route::get("cw-fd-tickets-latest","api\CWFDController@getLatestTicketExportV2");
Route::get("cw-fd-update-all","api\CWFDController@updateAll");


//mint360 fd
Route::post("mint360-fd-groups","api\Mint360FDController@getAllGroups");
Route::post("mint360-fd-companies","api\Mint360FDController@getAllCompanies");
Route::post("mint360-fd-agents","api\Mint360FDController@getAllAgents");
Route::post("mint360-fd-contacts","api\Mint360FDController@getAllContacts");
Route::post("mint360-fd-tickets","api\Mint360FDController@getAllTicketsV2");
Route::get("mint360-fd-tickets-latest","api\Mint360FDController@getLatestTicketExportV2");
Route::get("mint360-fd-update-all","api\Mint360FDController@updateAll");

//clique creative fd
Route::post("cc-fd-groups","api\CliqueCreativeFDController@getAllGroups");
Route::post("cc-fd-companies","api\CliqueCreativeFDController@getAllCompanies");
Route::post("cc-fd-agents","api\CliqueCreativeFDController@getAllAgents");
Route::post("cc-fd-contacts","api\CliqueCreativeFDController@getAllContacts");
Route::post("cc-fd-tickets","api\CliqueCreativeFDController@getAllTicketsV2");
Route::get("cc-fd-tickets-latest","api\CliqueCreativeFDController@getLatestTicketExportV2");
Route::get("cc-fd-update-all","api\CliqueCreativeFDController@updateAll");



//smartsourcing zd
Route::post("ss-zd-ticket-metrics","api\SSZDController@getAllTicketsV2");
Route::post("ss-zd-users","api\SSZDController@getAllUsers");
Route::post("ss-zd-groups","api\SSZDController@getAllGroups");
Route::post("ss-zd-organizations","api\SSZDController@getAllOrganizations");
Route::get("ss-zd-tickets-latest","api\SSZDController@getLatestTickets");
Route::get("ss-zd-update-all","api\SSZDController@updateAll");
Route::get("ss-zd-ticket-metric-events-latest","api\SSZDController@getLatestTicketMetricEvents");


//James Test
//Zendesk ticket api
Route::post("ssh-zn-tickets", "api\ZendeskTicketController@getTickets");
Route::post("ssj-zn-tickets", "api\ZNJamesTicketController@getAllTickets");


//smartsourcing zd_v2
Route::post("sss-zd-users","api\SSSZDController@getAllUsers");
Route::post("sss-zd-organizations","api\SSSZDController@getAllOrganizations");
Route::post("sss-zd-groups","api\SSSZDController@getAllGroups");
Route::get("sss-zd-tickets-latest","api\SSSZDController@getLatestTickets");
Route::get("sss-zd-update-all","api\SSSZDController@updateAll");
Route::get("sss-zd-ticket-metric-events-latest","api\SSSZDController@getLatestTicketMetricEvents");


//stoneandtile fd
Route::post("stoneandtile-fd-groups","api\StoneandTileFDController@getAllGroups");
Route::post("stoneandtile-fd-companies","api\StoneandTileFDController@getAllCompanies");
Route::post("stoneandtile-fd-agents","api\StoneandTileFDController@getAllAgents");
Route::post("stoneandtile-fd-contacts","api\StoneandTileFDController@getAllContacts");
Route::post("stoneandtile-fd-tickets","api\StoneandTileFDController@getAllTicketsV2");
Route::get("stoneandtile-fd-tickets-latest","api\StoneandTileFDController@getLatestTicketExportV2");
Route::get("stoneandtile-fd-update-all","api\StoneandTileFDController@updateAll");



//belleproperty fd
Route::post("belle-property-fd-groups","api\BellePropertyFDController@getAllGroups");
Route::post("belle-property-fd-companies","api\BellePropertyFDController@getAllCompanies");
Route::post("belle-property-fd-agents","api\BellePropertyFDController@getAllAgents");
Route::post("belle-property-fd-contacts","api\BellePropertyFDController@getAllContacts");
Route::post("belle-property-fd-tickets","api\BellePropertyFDController@getAllTicketsV2");
Route::get("belle-property-fd-tickets-latest","api\BellePropertyFDController@getLatestTicketExportV2");
Route::get("belle-property-fd-update-all","api\BellePropertyFDController@updateAll");


//beercartel fd
Route::post("beer-cartel-fd-groups","api\BeerCartelFDController@getAllGroups");
Route::post("beer-cartel-fd-companies","api\BeerCartelFDController@getAllCompanies");
Route::post("beer-cartel-fd-agents","api\BeerCartelFDController@getAllAgents");
Route::post("beer-cartel-fd-contacts","api\BeerCartelFDController@getAllContacts");
Route::post("beer-cartel-fd-tickets","api\BeerCartelFDController@getAllTicketsV2");
Route::get("beer-cartel-fd-tickets-latest","api\BeerCartelFDController@getLatestTicketExportV2");
Route::get("beer-cartel-fd-time-entry","api\BeerCartelFDController@getLatestTimeEntryV2");
Route::get("beer-cartel-fd-update-all","api\BeerCartelFDController@updateAll");


//bluerock fd
Route::post("blue-rock-fd-groups","api\BlueRockFDController@getAllGroups");
Route::post("blue-rock-fd-companies","api\BlueRockFDController@getAllCompanies");
Route::post("blue-rock-fd-agents","api\BlueRockFDController@getAllAgents");
Route::post("blue-rock-fd-contacts","api\BlueRockFDController@getAllContacts");
Route::post("blue-rock-fd-tickets","api\BlueRockFDController@getAllTicketsV2");
Route::get("blue-rock-fd-tickets-latest","api\BlueRockFDController@getLatestTicketExportV2");
Route::get("blue-rock-fd-update-all","api\BlueRockFDController@updateAll");



//uptick fd
Route::post("uptick-fd-groups","api\UptickFDController@getAllGroups");
Route::post("uptick-fd-companies","api\UptickFDController@getAllCompanies");
Route::post("uptick-fd-agents","api\UptickFDController@getAllAgents");
Route::post("uptick-fd-contacts","api\UptickFDController@getAllContacts");
Route::post("uptick-fd-tickets","api\UptickFDController@getAllTicketsV2");
Route::get("uptick-fd-tickets-latest","api\UptickFDController@getLatestTicketExportV2");
Route::get("uptick-fd-update-all","api\UptickFDController@updateAll");
