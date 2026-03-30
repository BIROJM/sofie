<?php

class CarteController extends \BaseController {

	public function index()
	{
		$regions = Region::all();
		return View::make('cartes.index')->with('regions', $regions);
	}
	
	public function selectRegion($id)
	{
		/*$region = DB::Table('t_region')
				->where('t_region.IDRegion', '=' , $id)
				->select()
				->get();*/
		$region = Region::where('IDRegion', '=', $id)->firstOrFail();
		//return Response::make($region->IDRegion);
		return View::make('cartes.indexcarte')->with('region', $region);
	}
	
		
	public function selectAllOuvrages()
	{
		$allOuvrages = DB::Table('t_ouvrage')
		->join('t_statutpanne','t_ouvrage.StatutPanne', '=', 't_statutpanne.id')
		->join('t_localite','t_ouvrage.IDLocalite','=','t_localite.IDLocalite')
		->whereRAW('t_ouvrage.dateStatut is not Null')
		->whereRAW('t_ouvrage.deleted_at is Null')
		->whereRAW('t_ouvrage.latitude is not Null')
		->whereRAW('t_ouvrage.longitude is not Null')
		->select('t_ouvrage.CodeOuvrage', 't_ouvrage.Latitude', 't_ouvrage.Longitude', 't_ouvrage.TypeOuvrage',  't_ouvrage.dateStatut', 't_statutpanne.icone', 't_ouvrage.validated', 't_statutpanne.libelleStatut', 't_localite.NomLocalite')
		->get();
		foreach($allOuvrages as $ouvrage)
		{
			if (empty($ouvrage->validated) || $ouvrage->validated = '0')
			{
				$ouvrage->icone = 'gray-pin1.png';
				$ouvrage->libelleStatut ='Non validé';
			}
		}
		return Response::json($allOuvrages);	
	}
		
	public function selectOuvrageByRegion($idRegion = 1)
	{ 
		$mode = Input::get('mode');
		if($mode == 'S')
		{
		$allOuvragesByRegion = DB::Table('t_ouvrage')
		->join('t_statutpanne','t_ouvrage.StatutPanne','=','t_statutpanne.id')
		->join('t_localite','t_ouvrage.IDLocalite','=','t_localite.IDLocalite')
		->whereRAW('t_ouvrage.IDRegion = ' . $idRegion)
		->whereRAW('t_ouvrage.dateStatut is not Null')
		->whereRAW('t_ouvrage.deleted_at is Null')
		->whereRAW('t_ouvrage.latitude is not Null')
		->whereRAW('t_ouvrage.longitude is not Null')
		->select('t_ouvrage.CodeOuvrage', 't_ouvrage.Latitude', 't_ouvrage.Longitude', 't_ouvrage.TypeOuvrage', 't_ouvrage.dateStatut', 't_ouvrage.validated', 't_statutpanne.icone', 't_statutpanne.libelleStatut', 't_localite.NomLocalite')
		->get();
		}
		elseif($mode == 'R')
		{
		$allOuvragesByRegion = DB::Table('t_ouvrage')
		->join('t_statutpanne','t_ouvrage.StatutPanne','=','t_statutpanne.id')
		->join('t_localite','t_ouvrage.IDLocalite','=','t_localite.IDLocalite')
		->whereRAW('t_ouvrage.IDRegion = ' . $idRegion)
		->whereRAW("t_ouvrage.sync = 'N'")
		->whereRAW('t_ouvrage.dateStatut is not Null')
		->whereRAW('t_ouvrage.deleted_at is Null')
		->whereRAW('t_ouvrage.latitude is not Null')
		->whereRAW('t_ouvrage.longitude is not Null')
		->select('t_ouvrage.CodeOuvrage', 't_ouvrage.Latitude', 't_ouvrage.Longitude', 't_ouvrage.TypeOuvrage', 't_ouvrage.dateStatut', 't_ouvrage.validated', 't_statutpanne.icone', 't_statutpanne.libelleStatut', 't_localite.NomLocalite')
		->get();
		}
		
		foreach($allOuvragesByRegion as $ouvrage)
		{
			if (empty($ouvrage->validated) || $ouvrage->validated = '0')
			{
				$ouvrage->icone = 'gray-pin1.png';
				$ouvrage->libelleStatut ='Non validé';
			}
		}
		//var_dump($allOuvragesByRegion); exit; 
		return Response::json($allOuvragesByRegion);	
	}
	
	public function getCityByRegion($idRegion)
	{
		$level = Input::get('level');
		if($level == 0)
		{
			if($idRegion == 6)
			{
				$allCityByRegion = DB::Table('t_ville')		
				->whereRAW('IDTypeCirconscription = 1')
				//->select('t_ouvrage.CodeOuvrage', 't_ouvrage.Latitude', 't_ouvrage.Longitude', 't_ouvrage.TypeOuvrage', 't_ouvrage.dateStatut', 't_ouvrage.validated', 't_statutpanne.icone', 't_statutpanne.libelleStatut', 't_localite.NomLocalite')
				->get();
			}
			else
			{
				$allCityByRegion = DB::Table('t_ville')		
				->whereRAW('idRegion = ' . $idRegion)
				->whereRAW('IDTypeCirconscription is not null')
				//->select('t_ouvrage.CodeOuvrage', 't_ouvrage.Latitude', 't_ouvrage.Longitude', 't_ouvrage.TypeOuvrage', 't_ouvrage.dateStatut', 't_ouvrage.validated', 't_statutpanne.icone', 't_statutpanne.libelleStatut', 't_localite.NomLocalite')
				->get();
			}
			
			
		}
		else
		{
			
			if($idRegion == 6)
			{
				$allCityByRegion = DB::Table('t_ville')		
				->whereRAW('IDTypeCirconscription = 1 or IDTypeCirconscription = ' . $level)
				//->select('t_ouvrage.CodeOuvrage', 't_ouvrage.Latitude', 't_ouvrage.Longitude', 't_ouvrage.TypeOuvrage', 't_ouvrage.dateStatut', 't_ouvrage.validated', 't_statutpanne.icone', 't_statutpanne.libelleStatut', 't_localite.NomLocalite')
				->get();
			}
			else
			{
				$allCityByRegion = DB::Table('t_ville')		
				->whereRAW('idRegion = ' . $idRegion)
				->whereRAW('IDTypeCirconscription = 1 or IDTypeCirconscription = ' . $level)
				//->select('t_ouvrage.CodeOuvrage', 't_ouvrage.Latitude', 't_ouvrage.Longitude', 't_ouvrage.TypeOuvrage', 't_ouvrage.dateStatut', 't_ouvrage.validated', 't_statutpanne.icone', 't_statutpanne.libelleStatut', 't_localite.NomLocalite')
				->get();
			}
		}
		return Response::json($allCityByRegion);	
	}
	
	public function loadRegion()
	{
		$sql = "select * from region";			
	}	
}