<?php

namespace App\Controllers;

use App\Models\ModelKorisnik;
use App\Models\ModelZahtevVer;
use App\Models\ModelRola;
use App\Models\ModelOglas;
use App\Models\ModelPrijava;
use App\Models\ModelStanje;


class Admin extends BaseController
{

	protected function pozovi($akcija, $data=[])
	{
		$data['controller'] = 'Admin';
		echo view('pocetna/header_admin.php', $data);
		echo view($akcija, $data);
		echo view('pocetna/footer.php', $data);
	}

	public function index()
	{
		$this->pozovi('pocetna/pocetna');
	}

	public function logout()
	{
		$this->pozovi('login/logout');
	}

	public function logout_action()
	{
		$this->session->destroy();
		return redirect()->to(site_url('Gost'));
	}


	public function o_nama()
	{
		$this->pozovi('o_nama/o_nama');
	}

	public function o_nama_action()
	{
		$imejl = $_POST['imejl'];
		$poruka = $_POST['poruka'];

		if ($imejl == "") return $this->pozovi('o_nama/o_nama_error');
		else{
			
			$email = \Config\Services::email();

			$email->setFrom($imejl, $imejl);
			$email->setTo("bookkingPSI@gmail.com");

			$email->setSubject('Žalba korisnika');
			$email->setMessage($poruka);

			$result = $email->send();
			return $this->pozovi('o_nama/o_nama_success');
		}
	}

	//Janko
	public function pretraga()
	{
		$oglasModel = new ModelOglas();
		$tekst = $this->request->getVar('pretraga');
		if ($tekst != null) {
			$oglasi = $oglasModel->where("Naslov LIKE '%$tekst%' OR Autor LIKE '%$tekst%' OR Opis LIKE '%$tekst%' OR IdS IN (SELECT IdS FROM stanjeoglasa WHERE Opis LIKE '%$tekst%')")
				->paginate(8, 'oglasi');
		} else {
			$oglasi = $oglasModel->paginate(8, 'oglasi');
		}
		$this->pozovi('pretraga/pretraga', [
			'oglasi' => $oglasi,
			'trazeno' => $this->request->getVar('pretraga'),
			'pager' => $oglasModel->pager,
			'mojiOglasi' => false
		]);
	}
	
	public function prikaz_zahtevi(){
		$zahtevVerModel = new ModelZahtevVer();
		$tekst = $this->request->getVar('pretraga');
		if ($tekst != null) {
			$zahtevi = $zahtevVerModel->where("Stanje LIKE '%$tekst%' OR (Podneo IN (SELECT IdK FROM korisnik WHERE Imejl LIKE '%$tekst%' OR Ime LIKE '%$tekst%' OR Prezime LIKE '%$tekst%'))")
				->paginate(6, 'zahtevi');
		} else {
			$zahtevi = $zahtevVerModel->paginate(6, 'zahtevi');
		}
		$data = [
            'zahtevi' => $zahtevi,
			'pager' => $zahtevVerModel->pager,
			'trazeno' => $this->request->getVar('pretraga'),
			'trenutni_korisnik' => 'Admin'
        ];
		$this->pozovi('zahtev_ver/prikaz_zahtevi', $data);
	}

	public function prikaz_zahtev($IdZ){
		$zahtevVerModel = new ModelZahtevVer();
		$zahtev = $zahtevVerModel->find($IdZ);
		$this->pozovi('zahtev_ver/prikaz_zahtev', ['zahtev' => $zahtev]);
	}

	public function prikaz_zahtev_fajl($IdZ){
		$zahtevVerModel = new ModelZahtevVer();
		$zahtev= $zahtevVerModel->find($IdZ);
		echo view('zahtev_ver/prikaz_zahtev_fajl', ['zahtev'=>$zahtev]);
	}

	public function razmotri_zahtev($IdZ){
		$akcija = $_POST['zahtev_dugme'];
		$zahtevVerModel = new ModelZahtevVer();
		$zahtev= $zahtevVerModel->find($IdZ);

		$admin = $this->session->get("korisnik");
		$odobrio = $admin->IdK;

		//odobravanje zahteva
		if($akcija == 'odobri') {
			$stanje = 'odobren';

			$korisnikModel = new ModelKorisnik();
			$rolaModel = new ModelRola();
			
			$rola = $rolaModel->where('Opis', 'Verifikovani')->first();
			$korisnikModel->update($zahtev->Podneo, ['IdR' => $rola->IdR]);
		}
		//odbijanje zahteva
		else if($akcija == 'odbij') {
			$stanje = 'odbijen';
		}

		$zahtevVerModel->update($IdZ, ['Stanje' => $stanje, 'Odobrio' => $odobrio]);
		return $this->pozovi('zahtev_ver/prikaz_zahtev_success', ['zahtev'=>$zahtev, 'stanje' => $stanje]);
	}

	//Rade
	public function obisanje_oglasa($id){
		$this->pozovi('pretraga/brisanje', ['IdO'=>$id]);
	}
	//Rade
	public function obrisi($id){
		$db      = \Config\Database::connect();
		$builder = $db->table('oglas'); 
		$stanjeModel = new ModelStanje(); 
		$stanje = $stanjeModel->where('Opis','Uklonjen')->first(); 
		$data = [
			'IdS' => $stanje->IdS 
		]; 
		$builder->where('IdO', $id);
		$builder->update($data);
		return redirect()->to(site_url("/bookking/Impl/public/Admin/pretraga/"));
	}


	public function nalog_pregled($IdK){
		$korisnikModel = new ModelKorisnik();
		$korisnik = $korisnikModel->find($IdK);
		$data['ime'] = $korisnik->Ime;
		$data['prezime'] = $korisnik->Prezime;
		$data['imejl'] = $korisnik->Imejl;
		$data['grad'] = $korisnik->Grad;
		$data['sifra'] = $korisnik->Sifra;
		$data['adresa'] = $korisnik->Adresa;
		$data['drzava'] = $korisnik->Drzava;
		$data['postBroj'] = $korisnik->PostBroj;
		$data['rola'] = 'Admin';
		$data['IdK'] = $IdK;
		$this->pozovi('nalog/nalog',$data);
	}

	public function nalog_brisanje($IdK){
		$this->pozovi('nalog/brisanje', ['IdK'=>$IdK]);
	}

	/* Doraditi sve u ostalim kontrolerima kada je nalog uklonjen*/
	public function nalog_brisanje_action($IdK){
		$korisnikModel = new ModelKorisnik();
		$korisnikModel->update($IdK, ['Stanje' => 'Uklonjen']);

		$stanjeModel = new ModelStanje(); 
		$stanje = $stanjeModel->where('Opis','Uklonjen')->first(); 

		$oglasModel = new ModelOglas();
		$oglasi = $oglasModel->dohvatiSveOglaseKorisnika($IdK);

		foreach ($oglasi as $oglas) {
			$oglasModel->update($oglas->IdO, ['IdS' => $stanje->IdS]);
		}

		$zahtevVerModel = new ModelZahtevVer();
		$zahtev = $zahtevVerModel->dohvatiPodnetZahtevKorisnika($IdK);
		if ($zahtev != null){
			$admin = $this->session->get("korisnik");
			$odobrio = $admin->IdK;
			$zahtevVerModel->update($zahtev->IdZ, ['Stanje' => 'odbijen', 'Odobrio' => $odobrio]);
		}

		return redirect()->to(site_url("/bookking/Impl/public/Admin/"));
	}

	public function svi_nalozi(){
		$rolaModel = new ModelRola();
		$rola = $rolaModel->where('Opis', 'Admin')->first();
		$korisnikModel = new ModelKorisnik();
		$tekst = $this->request->getVar('pretraga');
		if ($tekst != null) {
			$nalozi = $korisnikModel->where("Stanje='Vazeci' AND IdR!=$rola->IdR AND (Imejl LIKE '%$tekst%' OR Ime LIKE '%$tekst%' OR Prezime LIKE '%$tekst%')")
				->paginate(6, 'nalozi');
		} else {
			$nalozi = $korisnikModel->where(['Stanje' => 'Vazeci', 'IdR !=' => $rola->IdR ])->paginate(6, 'nalozi');
		}
		$data = [
            'nalozi' => $nalozi,
			'pager' => $korisnikModel->pager,
			'trazeno' => $this->request->getVar('pretraga')
        ];
		return $this->pozovi('nalog/nalog_svi', $data);
	}
	
}
