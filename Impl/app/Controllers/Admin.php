<?php

namespace App\Controllers;

use App\Models\ModelKorisnik;
<<<<<<< HEAD
use App\Models\ModelStanje;
=======
use App\Models\ModelZahtevVer;
use App\Models\ModelRola;
use App\Models\ModelOglas;
use App\Models\ModelPrijava;
use App\Models\ModelStanje;

>>>>>>> origin/master

class Admin extends BaseController
{

<<<<<<< HEAD
	protected function pozovi($akcija,$data=[])
=======
	protected function pozovi($akcija, $data=[])
>>>>>>> origin/master
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
		else return $this->pozovi('o_nama/o_nama_success');
	}

<<<<<<< HEAD
=======
	
	public function prikaz_zahtevi(){
		$zahtevVerModel = new \App\Models\ModelZahtevVer();
		$data = [
            'zahtevi' => $zahtevVerModel->where(['Stanje' => 'podnet'])->paginate(6, 'zahtevi'),
            'pager' => $zahtevVerModel->pager
        ];
		return $this->pozovi('zahtev_ver/prikaz_zahtevi', $data);
	}

	public function prikaz_zahtev($IdZ){
		$zahtevVerModel = new ModelZahtevVer();
		$zahtev = $zahtevVerModel->find($IdZ);
		return $this->pozovi('zahtev_ver/prikaz_zahtev', ['zahtev'=>$zahtev]);
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
>>>>>>> origin/master

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
<<<<<<< HEAD
=======


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
		$data = [
            'nalozi' => $korisnikModel->where(['Stanje' => 'Vazeci', 'IdR !=' => $rola->IdR ])->paginate(6, 'nalozi'),
            'pager' => $korisnikModel->pager
        ];
		return $this->pozovi('nalog/nalog_svi', $data);
	}
	
>>>>>>> origin/master
}
