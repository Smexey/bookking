<?php

namespace App\Controllers;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 *
 * @package CodeIgniter
 */

use CodeIgniter\Controller;
use App\Models\ModelOglas;
use App\Models\ModelStanje;

class BaseController extends Controller
{

	/**
	 * An array of helpers to be loaded automatically upon
	 * class instantiation. These helpers will be available
	 * to all other controllers that extend BaseController.
	 *
	 * @var array
	 */

	//Rade
	protected $helpers = ['form','url'];

	/**
	 * Constructor.
	 */
	public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
	{
		// Do Not Edit This Line
		parent::initController($request, $response, $logger);

		//--------------------------------------------------------------------
		// Preload any models, libraries, etc, here.
		//--------------------------------------------------------------------
		// E.g.:
		$this->session = \Config\Services::session(); 
	}

	public function nalog()
	{

		$korisnik = $this->session->get("korisnik");

		echo $korisnik->Ime . "<br>" . $korisnik->Imejl;
	}

	protected function pozovi($akcija, $data = []){
		throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	}

	//Rade
	public function pretraga(){
		$oglasModel = new ModelOglas(); 
		$stanjeModel = new ModelStanje();
		$stanje = $stanjeModel->where(['Opis'=>'Okacen'])->first();
		$tekst = $this->request->getVar('pretraga'); 
		if($tekst != null){
			$oglasi = $oglasModel ->like('Naslov',$tekst)
			->orLike('Autor',$tekst)
			->orLike('Opis',$tekst)
			->where('IdS',$stanje->IdS)
			->paginate(8, 'oglasi');
		}else {
			$oglasi = $oglasModel->where('IdS',$stanje->IdS)->paginate(8, 'oglasi');
		}
		$this->pozovi('pretraga/pretraga',[
            'oglasi' => $oglasi,
			"trazeno"=>$this->request->getVar('pretraga'),
            'pager' => $oglasModel->pager
        ]);
	}
	//Rade
	public function oglas($id){
		$oglasModel = new ModelOglas();
		$oglas = $oglasModel->find($id);
		
		$this->pozovi('pretraga/oglas',[
			'oglas' => $oglas,
			'trenutni_korisnik' => $this->session->get("korisnik")
        ]);
	}
}
