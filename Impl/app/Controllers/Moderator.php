<?php

namespace App\Controllers;

use App\Models\ModelKorisnik;
use App\Models\ModelZahtevVer;

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

	public function prikaz_zahtev($id){
		$zahtevVerModel = new ModelZahtevVer();
		$zahtev=$zahtevVerModel->find($id);
		$this->pozovi('zahtev_ver/prikaz_zahtev', ['zahtev'=>$zahtev]);
	}

	public function prikaz_zahtev_fajl($id){
		$zahtevVerModel = new ModelZahtevVer();
		$zahtev=$zahtevVerModel->find($id);
		echo view('zahtev_ver/prikaz_zahtev_fajl', ['zahtev'=>$zahtev]);
	}
}
