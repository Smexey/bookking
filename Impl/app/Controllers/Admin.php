<?php

namespace App\Controllers;

use App\Models\ModelKorisnik;
use App\Models\ModelZahtevVer;
use App\Models\ModelRola;
use App\Models\ModelOglas;
use App\Models\ModelPrijava;
use App\Models\ModelStanje;
use App\Models\ModelPregled;
use App\Models\ModelPregledUkupno;


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
		$stanjeModel = new Modelstanje();
		$stanja = $stanjeModel->findAll();

		$tekst = $this->request->getVar('pretraga');
		$IdS = $this->request->getVar('stanje');

		if ($tekst != null && $IdS != null) {
			if ($tekst[0] !== '#'){
				$oglasi = $oglasModel->where("IdS=$IdS AND (Naslov LIKE '%$tekst%' OR Autor LIKE '%$tekst%' OR Opis LIKE '%$tekst%')")
					->paginate(8, 'oglasi');
			}
			else{
				$tagOpis = substr($tekst, 1);
				$oglasi = $oglasModel->where("IdS=$IdS AND IdO IN (SELECT oglastag.IdO FROM oglastag WHERE oglastag.IdT IN (SELECT tag.IdT FROM tag WHERE tag.Opis LIKE '%$tagOpis%'))")
					->paginate(8, 'oglasi');
			}
		}
		else if ($tekst!=null){
			if ($tekst[0] !== '#'){
				$oglasi = $oglasModel->where("Naslov LIKE '%$tekst%' OR Autor LIKE '%$tekst%' OR Opis LIKE '%$tekst%'")
				->paginate(8, 'oglasi');
			}
			else{
				$tagOpis = substr($tekst, 1);
				$oglasi = $oglasModel->where("IdO IN (SELECT oglastag.IdO FROM oglastag WHERE oglastag.IdT IN (SELECT tag.IdT FROM tag WHERE tag.Opis LIKE '%$tagOpis%'))")
					->paginate(8, 'oglasi');
			}
		}
		else if ($IdS!=null){
			$oglasi = $oglasModel->where("IdS=$IdS")->paginate(8, 'oglasi');
		}
		else {
			$oglasi = $oglasModel->paginate(8, 'oglasi');
		}

		$this->pozovi('pretraga/pretraga', [
			'oglasi' => $oglasi,
			'trazeno' => $this->request->getVar('pretraga'),
			'pager' => $oglasModel->pager,
			'mojiOglasi' => false,
			'stanja' => $stanja
		]);
	}
	
	public function prikaz_zahtevi(){
		$zahtevVerModel = new ModelZahtevVer();
		$tekst = $this->request->getVar('pretraga');
		$stanje = $this->request->getVar('stanje');
		if ($tekst != null) {
			$zahtevi = $zahtevVerModel->where("Stanje LIKE '%$stanje%' AND (Podneo IN (SELECT IdK FROM korisnik WHERE Imejl LIKE '%$tekst%' OR Ime LIKE '%$tekst%' OR Prezime LIKE '%$tekst%'))")
				->paginate(6, 'zahtevi');
		} else {
			$zahtevi = $zahtevVerModel->where("Stanje LIKE '%$stanje%'")->paginate(6, 'zahtevi');
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
		if ($zahtev == null){
			return redirect()->to(site_url('/Admin'));
		}
		$this->session->set('zahtev', $zahtev);
		$this->pozovi('zahtev_ver/prikaz_zahtev', ['zahtev' => $zahtev]);
	}

	public function prikaz_zahtev_fajl(){
		$zahtev = $this->session->get('zahtev');
		if($zahtev == null){
			return redirect()->to(site_url('/Admin'));
		}
		echo view('zahtev_ver/prikaz_zahtev_fajl', ['zahtev'=>$zahtev]);
	}

	public function razmotri_zahtev(){
		$zahtev = $this->session->get('zahtev');
		if($zahtev == null || $zahtev->Stanje !== 'podnet'){
			return redirect()->to(site_url('/Admin'));
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

		return redirect()->to(site_url("Admin/pretraga"));
	}


	public function nalog_pregled($IdK){
		$korisnikModel = new ModelKorisnik();
		$korisnik = $korisnikModel->find($IdK);
		if($korisnik == null){
			return redirect()->to(site_url("Admin"));
		}
		$rolaModel = new ModelRola();
		$rola = $rolaModel->find($korisnik->IdR);
		if ($rola->Opis === 'Admin'){
			return redirect()->to(site_url("Admin"));
		}
		$data['ime'] = $korisnik->Ime;
		$data['prezime'] = $korisnik->Prezime;
		$data['imejl'] = $korisnik->Imejl;
		$data['grad'] = $korisnik->Grad;
		$data['sifra'] = $korisnik->Sifra;
		$data['adresa'] = $korisnik->Adresa;
		$data['drzava'] = $korisnik->Drzava;
		$data['postBroj'] = $korisnik->PostBroj;
		if ($korisnik->Stanje == 'Vazeci')
			$data['rola'] = 'Admin';
		else 
			$data['rola'] = 'Pregled';
		$data['IdK'] = $IdK;
		if ($korisnik->Stanje === 'Vazeci'){
			$this->session->set('AIdK', $IdK);
		}
		if($korisnik->Stanje === 'Vazeci' && $rola->Opis !== 'Moderator' && $korisnik->IdMod == null){
			$this->session->set('PIdK', $IdK);
		}
		$data['IdMod'] = $korisnik->IdMod;
		$data['opisRole'] = $rola->Opis;
		$this->pozovi('nalog/nalog',$data);
	}

	public function nalog_brisanje(){
		$IdK = $this->session->get('AIdK');
		if ($IdK == null){
			return redirect()->to(site_url("Admin"));
		}
		$this->pozovi('nalog/brisanje', ['IdK'=>$IdK]);
	}

	/* Doraditi sve u ostalim kontrolerima kada je nalog uklonjen*/
	public function nalog_brisanje_action(){
		$IdK = $this->session->get('AIdK');
		if ($IdK == null){
			return redirect()->to(site_url("Admin"));
		}

		$korisnikModel = new ModelKorisnik();
		$korisnikModel->update($IdK, ['Stanje' => 'Uklonjen', 'IdMod' => null]);

		//azuriranje stanja uklonjenog korisnika u Uklonjen
		$korisnik = $korisnikModel->find($IdK);
		if ($korisnik->IdMod != null){
			$korisnikModel->update($korisnik->IdMod, ['Stanje' => 'Uklonjen']);
		}

		//ako je uklonjeni korisnik bio moderator onda raskidamo vezu iz osnovnog naloga korisnika ka ovom moderatorskom
		$rolaModel = new ModelRola();
		$moderatorRola = $rolaModel->where('Opis', 'Moderator')->first();
		if ($korisnik->IdR == $moderatorRola->IdR){
			$osnovniKorisnik = $korisnikModel->where('IdMod', $IdK)->first();
			if ($osnovniKorisnik!=null){
				$korisnikModel->update($osnovniKorisnik->IdK, ['IdMod' => null]);
				//sendmail ako je uklonjeni bio moderator onda na korisnikov
				$message = "Zdravo " .$osnovniKorisnik->Ime. ",";
				$message .= "\n\nNažalost, nadalje niste deo moderatorskog tima sajta bookking.com";
				$message .= "\nVaš moderatorski nalog je uklonjen!";
		
				$email = \Config\Services::email();
		
				$email->setFrom('bookkingPSI@gmail.com', 'Bookking');
				$email->setTo($osnovniKorisnik->Imejl);
		
				$email->setSubject('Uklanjanje moderatorskog naloga');
				$email->setMessage($message);
		
				$result = $email->send();
			}
		}

		$stanjeModel = new ModelStanje(); 
		$stanje = $stanjeModel->where('Opis','Uklonjen')->first(); 

		$oglasModel = new ModelOglas();
		$oglasi = $oglasModel->dohvatiSveOglaseKorisnika($IdK);
		//azuriranje stanja svih oglasa uklonjenog korisnika na Uklonjen
		foreach ($oglasi as $oglas) {
			$oglasModel->update($oglas->IdO, ['IdS' => $stanje->IdS]);
		}

		//odbijanje podnetog zahteva uklonjenog korisnika ako je on postojao
		$zahtevVerModel = new ModelZahtevVer();
		$zahtev = $zahtevVerModel->dohvatiPodnetZahtevKorisnika($IdK);
		if ($zahtev != null){
			$admin = $this->session->get("korisnik");
			$odobrio = $admin->IdK;
			$zahtevVerModel->update($zahtev->IdZ, ['Stanje' => 'odbijen', 'Odobrio' => $odobrio]);
		}

		//sendmail na korisnikov mejl
		$message = "Zdravo " .$korisnik->Ime. ",";
		$message .= "\n\nNažalost,";
		$message .= "\nVaš nalog i sve vezano za njega je uklonjeno sa sajta bookking.com!";

		$email = \Config\Services::email();

		$email->setFrom('bookkingPSI@gmail.com', 'Bookking');
		$email->setTo($korisnik->Imejl);

		$email->setSubject('Uklanjanje naloga');
		$email->setMessage($message);

		$result = $email->send();
		$this->session->remove('AIdK');

		return redirect()->to(site_url("/bookking/Impl/public/Admin/"));
	}

	public function svi_nalozi(){
		$rolaModel = new ModelRola();
		$role = $rolaModel->where('Opis !=', 'Admin')->findAll();
		$adminRola = $rolaModel->where('Opis', 'Admin')->first();
		$korisnikModel = new ModelKorisnik();

		$tekst = $this->request->getVar('pretraga');
		$IdR = $this->request->getVar('rola');

		if ($tekst != null && $IdR != null) {
			$nalozi = $korisnikModel->where("Stanje='Vazeci' AND IdR=$IdR AND (Imejl LIKE '%$tekst%' OR Ime LIKE '%$tekst%' OR Prezime LIKE '%$tekst%')")
				->paginate(6, 'nalozi');
		} 
		else if ($tekst != null){
			$nalozi = $korisnikModel->where("Stanje='Vazeci' AND IdR!=$adminRola->IdR AND (Imejl LIKE '%$tekst%' OR Ime LIKE '%$tekst%' OR Prezime LIKE '%$tekst%')")
				->paginate(6, 'nalozi');
		}
		else if ($IdR != null){
			$nalozi = $korisnikModel->where(['Stanje' => 'Vazeci', 'IdR' => $IdR ])->paginate(6, 'nalozi');
		}
		else {
			$nalozi = $korisnikModel->where(['Stanje' => 'Vazeci', 'IdR !=' => $adminRola->IdR ])->paginate(6, 'nalozi');
		}
		$data = [
            'nalozi' => $nalozi,
			'pager' => $korisnikModel->pager,
			'trazeno' => $this->request->getVar('pretraga'),
			'role' => $role
        ];
		return $this->pozovi('nalog/nalog_svi', $data);
	}

	public function nalog_promocija(){
		$IdK = $this->session->get('PIdK');
		if ($IdK == null){
			return redirect()->to(site_url("Admin"));
		}

		$rolaModel = new ModelRola();
		$rola = $rolaModel->where('Opis', 'Moderator')->first();
		$this->pozovi('nalog/promocija', ['IdK'=>$IdK]);
	}

	public function nalog_promocija_action(){
		$IdK = $this->session->get('PIdK');
		if ($IdK == null){
			return redirect()->to(site_url("Admin"));
		}

		$korisnikModel = new ModelKorisnik();
		$brModeratora = count($korisnikModel->like('Imejl', 'moderator')->findAll()) + 1;
		$imejl = "moderator".$brModeratora."@bookking.com";

		$rolaModel = new ModelRola();
		$rola = $rolaModel->where('Opis', 'Moderator')->first();

		$promovisaniKorisnik = $korisnikModel->find($IdK);

		$korisnikModel->save([
			'Imejl'  => $imejl,
			'Sifra'  => $promovisaniKorisnik->Sifra,
			'Ime'  => $promovisaniKorisnik->Ime,
			'Prezime'  => $promovisaniKorisnik->Prezime,
			'Adresa'  => 'X',
			'Grad'  => 'X',
			'Drzava'  => 'X',
			'PostBroj'  => 11000,
			'Stanje' => 'Vazeci',
			'IdR'  => $rola->IdR,
		]);
		
		$noviModerator = $korisnikModel->where('Imejl', $imejl)->first();
		
		$korisnikModel->update($promovisaniKorisnik->IdK, ['IdMod' => $noviModerator->IdK]);

		//sendmail
		$message = "Zdravo " .$promovisaniKorisnik->Ime. ",";
		$message .= "\n\nČestitamo! Postali ste deo moderatorskom tima sajta bookking.com!";
		$message .= "\nPodaci o novom moderatorskom nalogu nalaze se dalje u mejlu.";
		$message .= "\n\nImejl: ". $imejl;
		$message .= "\nŠifra: ". $promovisaniKorisnik->Sifra;

		$email = \Config\Services::email();

		$email->setFrom('bookkingPSI@gmail.com', 'Bookking');
		$email->setTo($promovisaniKorisnik->Imejl);

		$email->setSubject('Promocija u moderatora');
		$email->setMessage($message);

		$result = $email->send();

		$this->session->remove('PIdK');
		return redirect()->to(site_url("/bookking/Impl/public/Admin/"));
	}
	
	//Janko
	public function admin_pregled($zaPrikaz = null){
		$pregledUkupnoModel = new ModelPregledUkupno();
		$generalniPregled = $pregledUkupnoModel->find(1);
		$pregledModel = new ModelPregled();
		$dnevniPregledi = $pregledModel->orderBy('IdPr', 'DESC')->FindAll(7, 0);

		$generalniPregled->BrKupovina += $dnevniPregledi[0]->BrKupovina;
		$generalniPregled->BrOglasa += $dnevniPregledi[0]->BrOglasa;
		$generalniPregled->BrKorisnika += $dnevniPregledi[0]->BrKorisnika;
		$generalniPregled->BrLogovanja += $dnevniPregledi[0]->BrLogovanja;

		$korisnikModel = new ModelKorisnik();
		$najKupac = $korisnikModel->find($dnevniPregledi[0]->NajKupac);
		//find vraca findall ako je parametar-id jednak null ili ako ga nema
		if (is_array($najKupac)){
			$najKupacImejl = 'Nema';
			$najKupacIdK = null;
		}
		else{
			$najKupacImejl = $najKupac->Imejl;
			$najKupacIdK = $najKupac->IdK;
		}
		$najProdavac = $korisnikModel->find($dnevniPregledi[0]->NajProdavac);
		if (is_array($najProdavac)){
			$najProdavacImejl = 'Nema';
			$najProdavacIdK = null;
		}
		else{
			$najProdavacImejl = $najProdavac->Imejl;
			$najProdavacIdK = $najProdavac->IdK;
		}
		$data = [
			'generalniPregled' => $generalniPregled,
			'pregledi' => $dnevniPregledi,
			'zaPrikaz' => $zaPrikaz,
			'najKupacImejl' => $najKupacImejl,
			'najKupacIdK' => $najKupacIdK,
			'najProdavacImejl' => $najProdavacImejl,
			'najProdavacIdK' => $najProdavacIdK
		];
		$this->pozovi('admin/pregled', $data);
	}

	public function admin_pregled_azuiranje(){
		$pregledUkupnoModel = new ModelPregledUkupno();
		$generalniPregled = $pregledUkupnoModel->find(1);
		$pregledModel = new ModelPregled();
		$dnevniPregledi = $pregledModel->orderBy('IdPr', 'DESC')->FindAll(7, 0);

		$generalniPregled->BrKupovina += $dnevniPregledi[0]->BrKupovina;
		$generalniPregled->BrOglasa += $dnevniPregledi[0]->BrOglasa;
		$generalniPregled->BrKorisnika += $dnevniPregledi[0]->BrKorisnika;
		$generalniPregled->BrLogovanja += $dnevniPregledi[0]->BrLogovanja;

		$korisnikModel = new ModelKorisnik();
		$najKupac = $korisnikModel->find($dnevniPregledi[0]->NajKupac);
		//find vraca findall ako je parametar-id jednak null ili ako ga nema
		if (is_array($najKupac)){
			$najKupacImejl = 'Nema';
			$najKupacIdK = null;
		}
		else{
			$najKupacImejl = $najKupac->Imejl;
			$najKupacIdK = $najKupac->IdK;
		}
		$najProdavac = $korisnikModel->find($dnevniPregledi[0]->NajProdavac);
		if (is_array($najProdavac)){
			$najProdavacImejl = 'Nema';
			$najProdavacIdK = null;
		}
		else{
			$najProdavacImejl = $najProdavac->Imejl;
			$najProdavacIdK = $najProdavac->IdK;
		}
		$data = [
			'generalniPregled' => $generalniPregled,
			'pregledi' => $dnevniPregledi,
			'najKupacImejl' => $najKupacImejl,
			'najKupacIdK' => $najKupacIdK,
			'najProdavacImejl' => $najProdavacImejl,
			'najProdavacIdK' => $najProdavacIdK
		];
		
		//header('Content-Type: application/json');
		//echo json_encode($data);
		$this->response->setContentType('Content-Type: application/json');
		echo json_encode($data);
		return; 
	}
}
