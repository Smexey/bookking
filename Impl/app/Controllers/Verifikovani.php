<?php

namespace App\Controllers;

use App\Models\ModelKorisnik;
use App\Models\ModelOglas;
use App\Models\ModelStanje;

class Verifikovani extends BaseController
{

	protected function pozovi($akcija,$data=[])
	{
		$data['controller'] = 'Verifikovani';
		echo view('pocetna/header_verifikovan.php', $data);
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

	//Rade
	public function moji_oglasi(){
		$korisnik = $this->session->get("korisnik");
		$oglasModel = new ModelOglas(); 
		$stanjeModel = new ModelStanje();
		$stanje = $stanjeModel->where(['Opis'=>'Okacen'])->first();
		$tekst = $this->request->getVar('pretraga'); 
		if($tekst != null){
			$oglasi = $oglasModel ->like('Naslov',$tekst)
			->orLike('Autor',$tekst)
			->orLike('Opis',$tekst)
			->where('IdS',$stanje->IdS)
			->where('IdK',$korisnik->IdK)
			->paginate(8, 'oglasi');
		}else {
			$oglasi = $oglasModel->where('IdS',$stanje->IdS)
			->where('IdK',$korisnik->IdK)->paginate(8, 'oglasi');
		}
		$this->pozovi('pretraga/pretraga',[
            'oglasi' => $oglasi,
			"trazeno"=>$this->request->getVar('pretraga'),
            'pager' => $oglasModel->pager
        ]);
	}

}
