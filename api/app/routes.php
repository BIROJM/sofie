<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('auth/login');
});

Route::get('ouvrage/search','OuvrageController@search');

Route::get('ouvrage/listPannes/{n}','OuvrageController@listPannes');

Route::resource('region', 'RegionController');

Route::resource('agent', 'AgentController');

Route::resource('reparateur', 'ReparateurController');

Route::resource('comite', 'ComiteController');

Route::resource('localite', 'LocaliteController');

Route::resource('ouvrage', 'OuvrageController');

Route::resource('user', 'UserController');

Route::resource('panne', 'PanneController');

Route::controller('auth', 'AuthController');

Route::get('ouvrage/selectLocalites/{n}','OuvrageController@selectLocalites');

Route::get('ouvrage/selectComites/{n}','OuvrageController@selectComites');

Route::get('ouvrage/selectReparateurs/{n}','OuvrageController@selectReparateurs');

//new
Route::get('carte/getCityByRegion/{n}','CarteController@getCityByRegion');

Route::get('carte','CarteController@index');

Route::get('carte/selectOuvrageByRegion/{n}','CarteController@selectOuvrageByRegion');

Route::get('carte/selectAllOuvrages','CarteController@selectAllOuvrages');

Route::get('carte/selectRegion/{n}','CarteController@selectRegion');

Route::get('admin','AdminController@index');

Route::post('reparateur/storeAjax','ReparateurController@storeAjax');

Route::post('agent/storeAjax','AgentController@storeAjax');

Route::post('localite/storeAjax','LocaliteController@storeAjax');

Route::post('ouvrage/storeAjax','OuvrageController@storeAjax');


Route::post('comite/storeAjax','ComiteController@storeAjax');

Route::post('user/storeAjax','UserController@storeAjax');

Route::get('localite/selectAgentsForma/{n}','LocaliteController@selectAgentsForma');

//Route destinée aux API Mobile

Route::group(array('prefix' => 'api/v1'), function()
{
    Route::post('auth', 'ApiController@auth');

	Route::post('createForage', 'ApiController@createForage');

	Route::post('updateForageData', 'ApiController@updateForageData');
	
	Route::get('getForageDataByRegion/{n}', 'ApiController@getForageDataByRegion');
	
	Route::get('getForageDataByNumIRH/{n}', 'ApiController@getForageDataByNumIRH');
	
	Route::get('getConfig/{n}', 'ApiController@getConfig');
});



Route::post('postGenericEntity/{n}','SynchroController@postGenericEntity');

Route::get('synchroGetOuvrage/{n}','SynchroController@getOuvrages');

Route::get('synchroGetCollecte/{n}','SynchroController@getCollectes');

Route::get('synchroGetPanne/{n}','SynchroController@getPannes');

Route::get('synchroGetNotification/{n}','SynchroController@getNotifications');

Route::get('synchroGetAppelTelephonique/{n}','SynchroController@getAppelTelephoniques');

Route::post('notifyEntity','SynchroController@notifyEntity');

Route::post('getEntityKey','SynchroController@sendEntityKey');

Route::post('getOuvrageKey','SynchroController@getOuvrageKey');

Route::post('dataSynchroRegionaleCentrale/{n}','SynchroController@dataSynchroRegionaleCentrale'); 

Route::post('createEntity','SynchroController@createEntity');

Route::post('createOuvrage','SynchroController@createOuvrage'); 

Route::post('sendForageStatusSynchro','SynchroController@sendForageStatusSynchro');

//Route::post('sendSms','SynchroController@sendSms');

//Route::get('ouvrage/list', array('as' => 'ouvrage.list', 'uses' => 'OuvrageController@indexDtable'));