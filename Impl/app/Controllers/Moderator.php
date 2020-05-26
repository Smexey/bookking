<?php

namespace App\Controllers;

use App\Models\ModelKorisnik;
use App\Models\ModelZahtevVer;
use App\Models\ModelRola;
use App\Models\ModelOglas;
use App\Models\ModelPrijava;
use App\Models\ModelStanje;

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

	
	public function prikaz_zahtevi(){
		$zahtevVerModel = new ModelZahtevVer();
		$tekst = $this->request->getVar('pretraga');
		if ($tekst != null) {
			$zahtevi = $zahtevVerModel->where("Stanje='podnet' AND (Podneo IN (SELECT IdK FROM korisnik WHERE Imejl LIKE '%$tekst%' OR Ime LIKE '%$tekst%' OR Prezime LIKE '%$tekst%'))")
				->paginate(6, 'zahtevi');
		} else {
			$zahtevi = $zahtevVerModel->where('Stanje', 'podnet')->paginate(6, 'zahtevi');
		}
		$data = [
            'zahtevi' => $zahtevi,
			'pager' => $zahtevVerModel->pager,
			'trazeno' => $this->request->getVar('pretraga'),
			'trenutni_korisnik' => 'Moderator'
        ];
		$this->pozovi('zahtev_ver/prikaz_zahtevi', $data);
	}

	public function prikaz_zahtev($IdZ){
		$zahtevVerModel = new ModelZahtevVer();
		$zahtev = $zahtevVerModel->find($IdZ);
		if ($zahtev == null || $zahtev->Stanje !== 'podnet'){
			return redirect()->to(site_url('/Moderator'));
		}
		$this->session->set('zahtev', $zahtev);
		$this->pozovi('zahtev_ver/prikaz_zahtev', ['zahtev' => $zahtev]);
	}

	public function prikaz_zahtev_fajl(){
		$zahtev = $this->session->get('zahtev');
		if($zahtev == null || $zahtev->Stanje !== 'podnet'){
			return redirect()->to(site_url('/Moderator'));
		}
		echo view('zahtev_ver/prikaz_zahtev_fajl', ['zahtev'=>$zahtev]);
	}

	public function razmotri_zahtev(){
		$zahtev = $this->session->get('zahtev');
		if($zahtev == null){
			return redirect()->to(site_url('/Moderator'));
		}
		$akcija = $_POST['zahtev_dugme'];

		$moderator = $this->session->get("korisnik");
		$odobrio = $moderator->IdK;

		$korisnikModel = new ModelKorisnik();
		$promovisaniKorisnik = $korisnikModel->find($zahtev->Podneo);

		//odobravanje zahteva
		if($akcija == 'odobri') {
			$stanje = 'odobren';

			$rolaModel = new ModelRola();
			
			$rola = $rolaModel->where('Opis', 'Verifikovani')->first();
			$korisnikModel->update($zahtev->Podneo, ['IdR' => $rola->IdR]);

			//sendmail
			$message = "Zdravo " .$promovisaniKorisnik->Ime. ",";
			$message .= "\n\nČestitamo! Vaš zahtev za verifikaciju naloga je odobren!";
			$message .= "\nPostali ste verifikovani korisnik na sajtu bookking.com!";
			$message .= "\nNadalje sami brinete o uklanjanju oglasa i pošiljkama pri kupovini.";

			$email = \Config\Services::email();

			$email->setFrom('bookkingPSI@gmail.com', 'Bookking');
			$email->setTo($promovisaniKorisnik->Imejl);

			$email->setSubject('Promocija u verifikovanog korisnika');
			$email->setMessage($message);

			$result = $email->send();
		}
		//odbijanje zahteva
		else if($akcija == 'odbij') {
			$stanje = 'odbijen';

			//sendmail
			$message = "Zdravo " .$promovisaniKorisnik->Ime. ",";
			$message .= "\n\nNažalost, Vaš zahtev za verifikaciju naloga je odbijen.";
			$message .= "\nPokušajte ponovo u dogledno vreme!";

			$email = \Config\Services::email();

			$email->setFrom('bookkingPSI@gmail.com', 'Bookking');
			$email->setTo($promovisaniKorisnik->Imejl);

			$email->setSubject('Promocija u verifikovanog korisnika');
			$email->setMessage($message);

			$result = $email->send();
		}
		$zahtevVerModel = new ModelZahtevVer();
		$zahtevVerModel->update($zahtev->IdZ, ['Stanje' => $stanje, 'Odobrio' => $odobrio]);

		$this->session->remove('zahtev');
		return $this->pozovi('zahtev_ver/prikaz_zahtev_success', ['zahtev'=>$zahtev, 'stanje' => $stanje]);
	}
	
	//Rade
	/**
	 * Funkcija za pozivanje view-a za brisanje oglasa
	 *
	 * @return void
	 */
	public function brisanje_oglasa()
	{
		$oglas = $this->session->get('oglas');
		if ($oglas == null){
			return redirect()->to(site_url('/'));
		}

		$stanjeModel = new ModelStanje();
		$stanje = $stanjeModel->find($oglas->IdS);

		if ($stanje->Opis !== 'Okacen'){
			return redirect()->to(site_url('/'));
		}

		$this->pozovi('pretraga/brisanje', ['IdO' => $oglas->IdO]);
	}
	//Rade
	/**
	 * Funkcija za brisanje oglasa
	 *
	 * @return redirekcija_na_pretragu
	 */
	public function obrisi()
	{
		$oglas = $this->session->get('oglas');
		if ($oglas == null){
			return redirect()->to(site_url('/'));
		}

		$oglasModel = new ModelOglas();
		$stanjeModel = new ModelStanje(); 
		$stanje = $stanjeModel->where('Opis', 'Uklonjen')->first(); 
		$data = [
			'IdS' => $stanje->IdS 
		]; 
		$oglasModel->update($oglas->IdO, $data);

		$korisnikModel = new ModelKorisnik();
		$prodavac = $korisnikModel->find($oglas->IdK);

		$this->session->remove('oglas');

		//sendmail
		$message = "Zdravo " .$prodavac->Ime. ",";
		$message .= "\n\nNažalost, Vaš oglas je uklonjen sa sajta bookking.com.";

		$email = \Config\Services::email();

		$email->setFrom('bookkingPSI@gmail.com', 'Bookking');
		$email->setTo($prodavac->Imejl);

		$email->setSubject('Uklanjanje oglasa');
		$email->setMessage($message);

		$result = $email->send();

		return redirect()->to(site_url("Moderator/pretraga"));
	}


	//Janko
	public function pretraga(){
		$oglasModel = new ModelOglas(); 
		$stanjeModel = new ModelStanje();
		$prijavaModel = new ModelPrijava();
		$stanje = $stanjeModel->where(['Opis'=>'Okacen'])->first();
		$tekst = $this->request->getVar('pretraga'); 
		if($tekst != null){
			if ($tekst[0] !== '#'){
				$oglasi = $oglasModel->where("IdS=$stanje->IdS AND EXISTS(SELECT * FROM prijava WHERE prijava.IdO=oglas.IdO) AND (Naslov LIKE '%$tekst%' OR Autor LIKE '%$tekst%' OR Opis LIKE '%$tekst%')")
				->paginate(8, 'oglasi');
			}
			else{
				$tagOpis = substr($tekst, 1);
				$oglasi = $oglasModel->where("IdS=$stanje->IdS AND EXISTS(SELECT * FROM prijava WHERE prijava.IdO=oglas.IdO) AND IdO IN (SELECT oglastag.IdO FROM oglastag WHERE oglastag.IdT IN (SELECT tag.IdT FROM tag WHERE tag.Opis LIKE '%$tagOpis%'))")
					->paginate(8, 'oglasi');
			}
		}else {
			$oglasi = $oglasModel -> where("IdS=$stanje->IdS AND EXISTS(SELECT * FROM prijava WHERE prijava.IdO=oglas.IdO)")
			->paginate(8, 'oglasi');
		}
		$this->pozovi('pretraga/pretraga',[
			'oglasi' => $oglasi,
			"trazeno"=>$this->request->getVar('pretraga'),
			'pager' => $oglasModel->pager,
			'mojiOglasi' => false,
			'stanja' => []
		]);
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
		$data['rola'] = 'Moderator';
		$this->pozovi('nalog/nalog',$data);
	}

}
