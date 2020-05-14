<?php

namespace App\Controllers;

use App\Models\ModelKorisnik;
use App\Models\ModelOglas;
use App\Models\ModelStanje;

class Verifikovani extends BaseController
{

	protected function pozovi($akcija, $data=[])
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

	public function nalog(){
		$korisnik = $this->session->get("korisnik");
		$data['ime'] = $korisnik->Ime;
		$data['prezime'] = $korisnik->Prezime;
		$data['imejl'] = $korisnik->Imejl;
		$data['grad'] = $korisnik->Grad;
		$data['sifra'] = $korisnik->Sifra;
		$data['adresa'] = $korisnik->Adresa;
		$data['drzava'] = $korisnik->Drzava;
		$data['postBroj'] = $korisnik->PostBroj;
		$data['rola'] = 'Verifikovani';
		$this->pozovi('nalog/nalog',$data);
	}

	public function nalog_izmena(){
		$korisnik = $this->session->get("korisnik");
		$data['ime'] = $korisnik->Ime;
		$data['prezime'] = $korisnik->Prezime;
		$data['imejl'] = $korisnik->Imejl;
		$data['grad'] = $korisnik->Grad;
		$data['sifra'] = $korisnik->Sifra;
		$data['adresa'] = $korisnik->Adresa;
		$data['drzava'] = $korisnik->Drzava;
		$data['postBroj'] = $korisnik->PostBroj;
		$data['rola'] = 'Verifikovani';
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

		$korisnik = $this->session->get("korisnik");
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

		$id = $korisnik->IdK;
		$save = $korisnikModel->update($id,$data);
		
		$korisnik = $korisnikModel->find($id);
		$this->session->set("korisnik", $korisnik);

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
