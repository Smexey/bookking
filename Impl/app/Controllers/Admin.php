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
		else return $this->pozovi('o_nama/o_nama_success');
	}

	
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


	public function nalog($IdK){
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

	public function nalog_izmena(){
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
		$this->pozovi('nalog/nalog_izmena',$data);
	}

	public function nalog_izmena_action(){
		$ime = $_POST['ime'];
		$imejl = $_POST['imejl'];
		$sifra = $_POST['sifra'];
		$prezime = $_POST['prezime'];
		$adresa = $_POST['adresa'];
		$grad = $_POST['grad'];
		$drzava = $_POST['drzava'];
		$postBroj = $_POST['postBroj'];

		$korisnikModel = new ModelKorisnik();
		
		$data = [
			'Ime' => $ime,
			'Prezime'  => $prezime,
			'Imejl'  => $imejl,
			'Sifra'  => $sifra,
			'Adresa'  => $adresa,
			'Grad'  => $grad,
			'Drzava'  => $drzava,
			'PostBroj'  => $postBroj,
			];

		$id = $IdK;
		$save = $korisnikModel->update($id,$data);
		
		$korisnik = $korisnikModel->find($id);

		$data['ime'] = $korisnik->Ime;
		$data['prezime'] = $korisnik->Prezime;
		$data['imejl'] = $korisnik->Imejl;
		$data['grad'] = $korisnik->Grad;
		$data['sifra'] = $korisnik->Sifra;
		$data['adresa'] = $korisnik->Adresa;
		$data['drzava'] = $korisnik->Drzava;
		$data['postBroj'] = $korisnik->PostBroj;
		$this->pozovi('nalog/nalog',$data);	
	}
}
