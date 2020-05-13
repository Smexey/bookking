<?php

namespace App\Controllers;

use App\Models\ModelKorisnik;
use App\Models\ModelZahtevVer;
use App\Models\ModelRola;

class Moderator extends BaseController
{

	protected function pozovi($akcija, $data = [])
	{
		$data['controller'] = 'Moderator';
		echo view('pocetna/header_moderator.php', $data);
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
		$this->pozovi('zahtev_ver/prikaz_zahtevi', $data);
	}

	public function prikaz_zahtev($IdZ){
		$zahtevVerModel = new ModelZahtevVer();
		$zahtev = $zahtevVerModel->find($IdZ);
		$this->pozovi('zahtev_ver/prikaz_zahtev', ['zahtev'=>$zahtev]);
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

		$moderator = $this->session->get("korisnik");
		$odobrio = $moderator->IdK;

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
}
