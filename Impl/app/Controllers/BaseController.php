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
use App\Models\ModelPoruka;
use App\Models\ModelRazgovor;
use App\Models\ModelKorisnik;

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
	protected $helpers = ['form', 'url', 'html'];

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


	protected function pozovi($akcija, $data = [])
	{
		throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
	}

	//Rade
	public function pretraga()
	{
		$oglasModel = new ModelOglas();
		$stanjeModel = new ModelStanje();
		$stanje = $stanjeModel->where(['Opis' => 'Okacen'])->first();
		$tekst = $this->request->getVar('pretraga');
		if ($tekst != null) {
			if ($tekst[0] !== '#') {
				$oglasi = $oglasModel->where("IdS=$stanje->IdS AND (Naslov LIKE '%$tekst%' OR Autor LIKE '%$tekst%' OR Opis LIKE '%$tekst%')")
					->paginate(8, 'oglasi');
			} else {
				$tagOpis = substr($tekst, 1);
				$oglasi = $oglasModel->where("IdS=$stanje->IdS AND IdO IN (SELECT oglastag.IdO FROM oglastag WHERE oglastag.IdT IN (SELECT tag.IdT FROM tag WHERE tag.Opis LIKE '%$tagOpis%'))")
					->paginate(8, 'oglasi');
			}
		} else {
			$oglasi = $oglasModel->where('IdS', $stanje->IdS)->paginate(8, 'oglasi');
		}
		$this->pozovi('pretraga/pretraga', [
			'oglasi' => $oglasi,
			'trazeno' => $this->request->getVar('pretraga'),
			'pager' => $oglasModel->pager,
			'mojiOglasi' => false,
			'stanja' => []
		]);
	}
	//Rade
	public function oglas($id)
	{
		$oglasModel = new ModelOglas();
		$oglas = $oglasModel->find($id);

		$this->session->set('oglas', $oglas);

		$this->pozovi('pretraga/oglas', [
			'oglas' => $oglas,
			'trenutni_korisnik' => $this->session->get("korisnik")
		]);
	}



	public function getNizKorisnika($IdK)
	{
		$modelRazg = new ModelRazgovor();
		$modelKorisnik = new ModelKorisnik();
		$modelPoruka = new ModelPoruka();

		$niz = $modelRazg->where("Korisnik1", $IdK)->findAll();
		foreach ($niz as $key => $el) {
			if ($modelKorisnik->find($el->Korisnik2)->Stanje != "Vazeci") {
				unset($niz[$key]);
			}
		}

		$ret = [];
		foreach ($niz as $konv) {
			$por = $modelPoruka->find($konv->IdPo);
			array_push(
				$ret,
				[
					"Kor" => $modelKorisnik->find($konv->Korisnik2),
					"Datum" => $por->Datum,
					"LastPoruka" => $por->Tekst
				]
			);
		}

		return $ret;
	}


	public function otvoriKonverzaciju_action()
	{
		$korisnik = $this->session->get("korisnik");

		$selected = $_POST['korisnikPrimalac'];

		$porModel = new ModelPoruka();


		$svePor = $porModel->dohvatiPoruke($korisnik->IdK, $selected);

		$niz = $this->getNizKorisnika($korisnik->IdK);


		$this->pozovi("poruke/main", [
			"korisnici" => $niz,
			"selected" => $selected,
			"currentPoruke" => $svePor
		]);
	}


	public function otvoriPoruke_action()
	{
		$korisnik = $this->session->get("korisnik");

		$niz = $this->getNizKorisnika($korisnik->IdK);


		$this->pozovi("poruke/main", [
			"korisnici" => $niz,
		]);
	}

	public function poruke()
	{
		$this->otvoriPoruke_action();
	}

	public function posaljiPor_action()
	{
		$text = $_POST['text'];
		$korisnik1 = $this->session->get("korisnik")->IdK;
		$korisnik2 = $_POST['selected'];

		if ($text != "" && $korisnik2 != null) {
			$porModel = new ModelPoruka();

			$porModel->save([
				'Korisnik1'  => $korisnik1,
				'Korisnik2'  => $korisnik2,
				'Tekst'  => $text
			]);
		}


		$_POST['korisnikPrimalac'] = $korisnik2;
		if ($korisnik2 != null) $this->otvoriKonverzaciju_action();
		else $this->otvoriPoruke_action();
	}

	public function zapocniKonverzaciju()
	{
		$text = $_POST['knjiga'];

		$korisnik1 = $this->session->get("korisnik")->IdK;
		$korisnik2 = $_POST['primalac'];

		$razgModel = new ModelRazgovor();

		//insert or ignore insert if exists
		$razgModel->ignore(true)->insert([
			'Korisnik1'  => $korisnik1,
			'Korisnik2'  => $korisnik2,
		]);

		$razgModel->ignore(true)->insert([
			'Korisnik1'  => $korisnik2,
			'Korisnik2'  => $korisnik1,
		]);


		$porModel = new ModelPoruka();

		$porModel->save([
			'Korisnik1'  => $korisnik1,
			'Korisnik2'  => $korisnik2,
			'Tekst'  => $text
		]);
		$_POST['korisnikPrimalac'] = $korisnik2;
		$this->otvoriKonverzaciju_action();
	}
}
