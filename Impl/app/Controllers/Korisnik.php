<?php

namespace App\Controllers;

use App\Models\ModelZahtevVer;
use App\Models\ModelKorisnik;
use App\Models\ModelRola;
use App\Models\ModelOglas;
use App\Models\ModelOglasTag;
use App\Models\ModelPrijava;
use App\Models\ModelStanje;
use App\Models\ModelTag;
use App\Models\ModelKupovina;
use App\Models\ModelNacinKupovine;

class Korisnik extends BaseController
{

	protected function pozovi($akcija, $data = [])
	{
		$data['controller'] = 'Korisnik';
		echo view('pocetna/header_korisnik.php', $data);
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

	public function login_action()
	{
		$imejl = $_POST['imejl'];
		$sifra = $_POST['sifra'];

		$korisnik = (new ModelKorisnik())->asObject()->where("Imejl", $imejl)->where("Sifra", $sifra)->findAll();

		if (count($korisnik) == 1) $this->pozovi('login/login_success');
		else $this->pozovi('login/login_error');
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


	public function zahtev_ver()
	{
		$zahtevVerModel = new ModelZahtevVer();
		$korisnik = $this->session->get("korisnik");
		//Provera da li postoji neobradjen zahtev trebutnog korisnika
		if ($zahtevVerModel->proveraZahtevPodnet($korisnik->IdK)) {
			return $this->pozovi('zahtev_ver/slanje_zahteva_podnet');
		} else {
			$data['zahtevNeuspesan'] = '';
			return $this->pozovi('zahtev_ver/slanje_zahteva', $data);
		}
	}

	public function zahtev_ver_action()
	{
		try {
			//Nedefinisani, višestruki fajlovi i korupcioni napad na $_FILES se tretiraju kao greška
			if (
				!isset($_FILES['zahtevFajl']['error']) ||
				is_array($_FILES['zahtevFajl']['error'])
			) {

				throw new \Exception('Greška!');
			}

			//Provera da li je dokument priložen
			switch ($_FILES['zahtevFajl']['error']) {
				case UPLOAD_ERR_OK:
					break;
				case UPLOAD_ERR_NO_FILE:
					throw new \Exception('Morate prineti dokaz u vidu .pdf dokumenta!');
				default:
					throw new \Exception('Greška!');
			}

			//Provera maksimalne veličine dokumenta - 1MB
			$limit = 1000000;
			if ($_FILES['zahtevFajl']['size'] >= $limit) {
				throw new \Exception('Prekoračena je maksimalna veličina fajla(16 MB)!');
			}


			$imefajla = $_FILES['zahtevFajl']['name'];
			$ekstenzija = pathinfo($imefajla, PATHINFO_EXTENSION);
			//Provera ekstenzije dokumenta - mora biti .pdf
			if ($ekstenzija !== 'pdf') {
				throw new \Exception('Ekstenzija fajla mora biti .pdf!');
			}

			$fajl = file_get_contents($_FILES['zahtevFajl']['tmp_name']);

			$zahtevVerModel = new ModelZahtevVer();

			$korisnik = $this->session->get("korisnik");
			$podneo = $korisnik->IdK;
			$stanje = "podnet";

			$zahtevVerModel->save([
				'Stanje' => $stanje,
				'Fajl'  => $fajl,
				'Podneo' => $podneo
			]);
			//Ispis u slucaju da je zahtev uspesno poslat
			return $this->pozovi('zahtev_ver/slanje_zahteva_success');
		} catch (\Exception $th) {
			//Ispis greske
			$data['zahtevNeuspesan'] = $th->getMessage();
			return $this->pozovi('zahtev_ver/slanje_zahteva', $data);
		}
	}

	//Rade
	/**
	 * Funkcija za prikaz sopstvenih oglasa
	 *
	 * @return void
	 */
	public function moji_oglasi()
	{
		$korisnik = $this->session->get("korisnik");
		$oglasModel = new ModelOglas();
		$stanjeModel = new ModelStanje();
		$stanje = $stanjeModel->where(['Opis' => 'Okacen'])->first();
		$tekst = $this->request->getVar('pretraga');
		if ($tekst != null) {
			if ($tekst[0] !== '#'){
				$oglasi = $oglasModel->where("IdS=$stanje->IdS AND IdK=$korisnik->IdK AND (Naslov LIKE '%$tekst%' OR Autor LIKE '%$tekst%' OR Opis LIKE '%$tekst%')")
					->paginate(8, 'oglasi');
			}
			else{
				$tagOpis = substr($tekst, 1);
				$oglasi = $oglasModel->where("IdS=$stanje->IdS AND IdK=$korisnik->IdK AND IdO IN (SELECT oglastag.IdO FROM oglastag WHERE oglastag.IdT IN (SELECT tag.IdT FROM tag WHERE tag.Opis LIKE '%$tagOpis%'))")
					->paginate(8, 'oglasi');
			}
		} else {
			$oglasi = $oglasModel->where(['IdS' => $stanje->IdS, 'IdK' => $korisnik->IdK])->paginate(8, 'oglasi');
		}
		$this->pozovi('pretraga/pretraga', [
			'oglasi' => $oglasi,
			'trazeno' => $this->request->getVar('pretraga'),
			'pager' => $oglasModel->pager,
			'mojiOglasi' => true
		]);
	}

	//Rade
	/**
	 * Funkcija za prikaz form za dodavanje novog oglalsa
	 *
	 * @return void
	 */
	public function dodaj_oglas()
	{
		$this->pozovi('pretraga/dodajOglas');
	}

	//Rade
	/**
	 * Funkcija za proveru i dodavanje novog oglasa
	 *
	 * @return redirekt_na_taj_oglas
	 */
	public function nova_vest()
	{
		if (!$this->validate([
			'naslovnica' => 'uploaded[naslovnica]|max_size[naslovnica,1024]|mime_in[naslovnica,image/png,image/jpeg]',
			'naslov' => 'required|max_length[40]',
			'opis' => 'required|min_length[5]|max_length[200]',
			'autor' => 'required|max_length[50]',
			'cena' => 'required|regex_match[/^[0-9]{1,8}(\.[0-9]{2,2})?$/]'
		],
		[
			'naslovnica' => [
				'uploaded' => 'Potrebno je priložiti sliku naslovnice!',
				'max_size' => 'Prekoračena je maksimalna veličina slike(1 MB)!',
				'mime_in' => 'Prilog mora biti u obliku slike'
			],
			'naslov' => [
				'required' => 'Potrebno je uneti naslov!',
				'max_length' => 'Maksimalna dužina polja naslov je 40 karaktera!'
			],
			'opis' => [
				'required' => 'Potrebno je uneti opis!',
				'min_length' => 'Minimalna dužina polja opis je 5 karaktera!',
				'max_length' => 'Maksimalna dužina polja opis je 200 karaktera!'
			],
			'autor' => [
				'required' => 'Potrebno je uneti autora!',
				'max_length' => 'Maksimalna dužina polja autor je 50 karaktera!'
			],
			'cena' => [
				'required' => 'Potrebno je uneti cenu!',
				'regex_match' => 'Cena je ceo broj do 8 cifara ili broj na dve decimalne cifre!'
			]
		]))
			return $this->pozovi(
				'pretraga/dodajOglas',
				['errors' => $this->validator->getErrors()]
			);
		$tags = $this->request->getVar('tags');
		// $tags = strtolower($tags);
		$tags = preg_split("/[\s,]+/", $tags);
		if(count($tags) > 10){
			return $this->pozovi(
				'pretraga/dodajOglas',
				['errors' => ['Maksimalni broj tagova za jedan oglas je 10']]
			);
		}
		
		foreach ($tags as $tag) {
			if(strlen($tag) > 20){
				return $this->pozovi(
					'pretraga/dodajOglas',
					['errors' => ['Maksimalna dužina jednog taga je 20 karaktera']]
				);
			}
		}
		$stanjeModel = new ModelStanje();
		$stanje = $stanjeModel->where(['Opis' => 'Okacen'])->first();
		$korisnik = $this->session->get("korisnik");
		$oglasModel = new ModelOglas();
		$file = $this->request->getPost('naslovnica');
		$oglasModel->save([
			'IdK' => $korisnik->IdK,
			'IdS' => $stanje->IdS,
			'Autor' => $this->request->getVar('autor'),
			'Naslov' => $this->request->getVar('naslov'),
			'Opis' => $this->request->getVar('opis'),
			'Cena' => $this->request->getVar('cena'),
			'Naslovnica' => file_get_contents($_FILES['naslovnica']['tmp_name'])
		]);
		$lastOglasID = $oglasModel->getInsertID();

		// $tags = preg_match("/[\w]+/g",$tags);
		$tagModel = new ModelTag();
		$oglasTagModel = new ModelOglasTag();
		foreach ($tags as $tag) {
			if ($tag != "") {
				$tagProvera = $tagModel->where(['Opis' => $tag])->findAll();
				$ok = true;
				foreach ($tagProvera as $tp) {
					if (count($tagProvera) != 0) {
						$tagId = $tp->IdT;
						$ok = false;
					}
					break;
				}
				if ($ok) {
					$tagModel->save([
						'Opis' => $tag
					]);
					$tagId = $tagModel->getInsertID();
				}
				$oglasTagProvera = $oglasTagModel->where([
					'IdT' => $tagId,
					'IdO' => $lastOglasID
				])->findAll();
				$ok = true;
				foreach ($oglasTagProvera as $otp) {
					$ok = false;
					break;
				}
				if ($ok) {
					$oglasTagModel->save([
						'IdT' => $tagId,
						'IdO' => $lastOglasID
					]);
				}
			}
		}
		return redirect()->to(site_url("Korisnik/oglas/{$lastOglasID}"));
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

		$trenutniKorisnik = session()->get('korisnik');

		if ($trenutniKorisnik->IdK != $oglas->IdK){
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
		$stanje = $stanjeModel->where('Opis', 'Korisnik uklonio')->first(); 
		$data = [
			'IdS' => $stanje->IdS 
		]; 
		$oglasModel->update($oglas->IdO, $data);

		$this->session->remove('oglas');

		return redirect()->to(site_url("Korisnik/pretraga"));
	}
	//Rade
	/**
	 * Funkcija za prikaz forme za kupovinu
	 *
	 * @return void
	 */
	public function kupovina()
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

		$this->pozovi("kupovina/nacin_placanja", ['oglas' => $oglas]);
	}
	//Rade
	/**
	 * Funkcija za prikaz nacina odabira kupovine
	 *
	 * @return void
	 */
	public function kupovina_dalje()
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

		$this->session->set('nacin', $this->request->getVar('a'));
		if ("poruka" == $this->request->getVar('a')) {
			//redirect na prodavca 
			$_POST['knjiga'] = "Zdravo, želim da kupim knjigu \"" . $oglas->Naslov ."\"";
			$_POST['primalac'] = $oglas->IdK;
			$this->session->remove('oglas');
			$this->zapocniKonverzaciju();
		} else {
			$this->pozovi('kupovina/forma', [
				'oglas' => $oglas,
				'a' => $this->request->getVar('a')
			]);
		}
	}
	//Rade
	/**
	 * Funkcija za proveru i kupovinu oglasa
	 *
	 * @return void
	 */
	public function provera()
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

		$korisnikModel = new ModelKorisnik();
		$prodavac = $korisnikModel->find($oglas->IdK);

		if (
			$this->request->getVar('placanje') == 'Kartica'
			&& !$this->validate([
				'cardholder' => 'required|min_length[2]|max_length[50]',
				// 'brK'=>'required|valid_cc_number[amex]|
				// valid_cc_number[maestro]|valid_cc_number[visa]',
				'brK' => 'required|regex_match[/^[0-9]{12,12}$/]',
				'validThu' => 'required|regex_match[/(?:0[1-9]|1[0-2])\/[0-9]{2}/]',
				'cvv' => 'required|regex_match[/^[0-9]{3,4}$/]',
			],[
				'cardholder' => [
					'required' => 'Potrebno je uneti ime i prezime!',
					'min_length' => 'Minimalna dužina polja ime i prezime je 2 karaktera!',
					'max_length' => 'Maksimalna dužina polja ime i prezime je 50 karaktera!'
				],
				'brK' => [
					'required' => 'Potrebno je uneti broj kartice!',
					'regex_match' => 'Broj kartice nije u željenom formatu!'
				],
				'validThu' => [
					'required' => 'Potrebno je uneti validnost!',
					'regex_match' => 'Validnost nije u željenom formatu!'
				],
				'cvv' => [
					'required' => 'Potrebno je uneti CVV/CVC!',
					'regex_match' => 'CVV/CVC nije u željenom formatu!'
				]
			]) 
		)  
			return $this->pozovi(
				'kupovina/forma',
				['oglas' => $oglas, 'errors' => $this->validator->getErrors()]
			);
		else {
			//return $this->pozovi('pretraga/pretraga',[]);
			$kupac = $this->session->get('korisnik');
			$kupovinaModel = new ModelKupovina();
			$nacinKupovineModel = new ModelNacinKupovine();
			$nacin = $this->session->get('nacin');
			
			if ('sajt' == $nacin){
				$nacinKupovine = $nacinKupovineModel->where('Opis', 'Preko sajta')->first();
				//stanje oglasa se ne menja jer verifikovani 
				//ima vise knjiga za isti oglas i sam upravlja uklanjanjem oglasa
				
				//sendmail
				$message = "Zdravo " .$kupac->Ime. ",";
				$message .= "\n\nČestitamo! Uspešno ste podneli zahtev za kupovinu!";
				$message .= "\nDalje informacije ćete dobiti preko mejla prodavca:";
				$message .= "\nImejl prodavca: ". $prodavac->Imejl;

				$email = \Config\Services::email();

				$email->setFrom('bookkingPSI@gmail.com', 'Bookking');
				$email->setTo($kupac->Imejl);

				$email->setSubject('Kupovina olgasa');
				$email->setMessage($message);

				$result = $email->send();

				//sendmail
				$message = "Zdravo " .$prodavac->Ime. ",";
				$message .= "\n\nČestitamo! Imate novi zahtev za kupovinu!";
				$message .= "\nDalje je potrebno da stupite u kontakt sa kupcem:";
				$message .= "\nImejl kupca: ". $kupac->Imejl;

				$email = \Config\Services::email();

				$email->setFrom('bookkingPSI@gmail.com', 'Bookking');
				$email->setTo($prodavac->Imejl);

				$email->setSubject('Prodaja oglasa');
				$email->setMessage($message);

				$result = $email->send();
			}
			else if ('middleman' == $nacin){
				$nacinKupovine = $nacinKupovineModel->where('Opis', 'Preko middlemana')->first();
				$oglasModel = new ModelOglas();
				$stanjeModel = new ModelStanje();
				$stanjeOglasa = $stanjeModel->where('Opis', 'Kupljen')->first();
				//azuriranje stanja oglasa da je kupljen
				$oglasModel->update($oglas->IdO, ['IdS' => $stanjeOglasa->IdS]);

				//sendmail
				$message = "Zdravo " .$kupac->Ime. ",";
				$message .= "\n\nČestitamo! Uspešno ste obavili kupovinu!";
				$message .= "\nObavestićemo Vas kada pošiljka bude poslata na Vašu adresu.";

				$email = \Config\Services::email();

				$email->setFrom('bookkingPSI@gmail.com', 'Bookking');
				$email->setTo($kupac->Imejl);

				$email->setSubject('Kupovina olgasa');
				$email->setMessage($message);

				$result = $email->send();

				//sendmail
				$message = "Zdravo " .$prodavac->Ime. ",";
				$message .= "\n\nČestitamo! Vaš oglas je uspešno prodat!";
				$message .= "\nUskoro ćemo poslati pošiljku na adresu kupca.";

				$email = \Config\Services::email();

				$email->setFrom('bookkingPSI@gmail.com', 'Bookking');
				$email->setTo($prodavac->Imejl);

				$email->setSubject('Prodaja oglasa');
				$email->setMessage($message);

				$result = $email->send();
			}

			$kupovinaModel->save([
				'IdK' => $kupac->IdK,
				'IdO' => $oglas->IdO,
				'IdN' => $nacinKupovine->IdN
			]);
			
			$message = "Usesno obavljena kupovina! Očekujte dalja obavestenja preko email-a";

			$this->session->remove('oglas');

			$this->pozovi(
				'kupovina/kupljeno',
				['message' => $message]
			);
		}
	}

	//Rade
	/**
	 * Funkcija za redirekciju na pretragu nakon uspesne kupovine
	 *
	 * @return redirekt_na_pretragu
	 */
	public function uspesna_kupovina()
	{
		return redirect()->to(site_url("Korisnik/pretraga"));
	}

	//Rade
	/**
	 * Funkcija za prikaz forme za prijavu
	 *
	 * @return void
	 */
	public function prijava_forma()
	{
		$oglas = $this->session->get('oglas');
		if ($oglas == null){
			return redirect()->to(site_url('/'));
		}
		$this->pozovi('prijava/forma_prijave', []);
	}
	//Rade
	/**
	 * Funkcija za prijavu oglasa
	 *
	 * @return redirekt_na_pretragu
	 */
	public function prijava()
	{
		$prijavaModel = new ModelPrijava();

		$korisnik = $this->session->get("korisnik");
		$oglas = $this->session->get("oglas");
		if ($oglas == null){
			return redirect()->to(site_url('/'));
		}
		if (!$this->validate([
			'opisPrijave' => 'required|max_length[50]'
		],
		[
			'opisPrijave' => [
				'required' => 'Potrebno je uneti opis prijave!',
				'max_length' => 'Maksimalna dužina polja opisa prijave je 50 karaktera!'
			]
		])){
			return $this->pozovi('prijava/forma_prijave', ['errors' => $this->validator->getErrors()]);
		}
		$opis = $this->request->getVar('opisPrijave');

		$prijavaModel->insert([
			'IdK' => $korisnik->IdK,
			'Opis' => $opis,
			'IdO'  => $oglas->IdO
		]);
		$this->session->remove('oglas');
		return redirect()->to(site_url("Korisnik/pretraga"));
	}

	public function nalog_pregled($IdK)
	{
		$korisnikKojiPregleda = $this->session->get("korisnik");

		$korisnikModel = new ModelKorisnik();
		$korisnik = $korisnikModel->find($IdK);
		if ($korisnik == null || $korisnik->Stanje !== 'Vazeci'){
			return redirect()->to(site_url("Korisnik"));
		}
		$rolaModel = new ModelRola();
		$rola = $rolaModel->find($korisnik->IdR);
		if ($rola->Opis !== 'Korisnik' && $rola->Opis !== 'Verifikovani'){
			return redirect()->to(site_url("Korisnik"));
		}
		$data['ime'] = $korisnik->Ime;
		$data['prezime'] = $korisnik->Prezime;
		$data['imejl'] = $korisnik->Imejl;
		$data['grad'] = $korisnik->Grad;
		$data['sifra'] = $korisnik->Sifra;
		$data['adresa'] = $korisnik->Adresa;
		$data['drzava'] = $korisnik->Drzava;
		$data['postBroj'] = $korisnik->PostBroj;
		if ($korisnikKojiPregleda->IdK == $korisnik->IdK) {
			$data['rola'] = 'Korisnik';
		} else {
			$data['rola'] = 'Pregled';
		}
		$this->pozovi('nalog/nalog', $data);
	}

	public function nalog()
	{
		$korisnik = $this->session->get("korisnik");
		$data['ime'] = $korisnik->Ime;
		$data['prezime'] = $korisnik->Prezime;
		$data['imejl'] = $korisnik->Imejl;
		$data['grad'] = $korisnik->Grad;
		$data['sifra'] = $korisnik->Sifra;
		$data['adresa'] = $korisnik->Adresa;
		$data['drzava'] = $korisnik->Drzava;
		$data['postBroj'] = $korisnik->PostBroj;
		$data['rola'] = 'Korisnik';
		$this->pozovi('nalog/nalog', $data);
	}

	public function nalog_izmena()
	{
		$korisnik = $this->session->get("korisnik");
		$data['ime'] = $korisnik->Ime;
		$data['prezime'] = $korisnik->Prezime;
		$data['imejl'] = $korisnik->Imejl;
		$data['grad'] = $korisnik->Grad;
		$data['sifra'] = $korisnik->Sifra;
		$data['adresa'] = $korisnik->Adresa;
		$data['drzava'] = $korisnik->Drzava;
		$data['postBroj'] = $korisnik->PostBroj;
		$data['rola'] = 'Korisnik';
		$this->pozovi('nalog/nalog_izmena', $data);
	}

	public function nalog_izmena_action()
	{

		if (!$this->validate([
			'ime' => 'required',
			'prezime' => 'required',
			'sifra' =>  'required',
			'adresa' => 'required',
			'grad' => 'required',
			'drzava' => 'required',
			'postBroj' => 'required',
		])
		){
			$korisnik = $this->session->get("korisnik");
			$id = $korisnik->IdK;
			$data['ime'] = $korisnik->Ime;
			$data['prezime'] = $korisnik->Prezime;
			$data['imejl'] = $korisnik->Imejl;
			$data['grad'] = $korisnik->Grad;
			$data['sifra'] = $korisnik->Sifra;
			$data['adresa'] = $korisnik->Adresa;
			$data['drzava'] = $korisnik->Drzava;
			$data['postBroj'] = $korisnik->PostBroj;
			$data['rola'] = 'Korisnik';
			$data['errors'] = ['Sva polja su obavezna!'];
			return $this->pozovi('nalog/nalog_izmena', $data);
		}
		else if (!$this->validate([
			'ime' => 'max_length[30]',
			'prezime' => 'max_length[30]',
			'sifra' =>  'max_length[30]',
			'adresa' => 'max_length[30]',
			'grad' => 'max_length[30]',
			'drzava' => 'max_length[30]',
			'postBroj' => 'max_length[9]|numeric',
		],
		[
			'ime' => [
				'max_length' => 'Maksimalna dužina polja Ime je 30 karaktera!'
			],
			'prezime' => [
				'max_length' => 'Maksimalna dužina polja Prezime je 30 karaktera!'
			],
			'sifra' => [
				'max_length' => 'Maksimalna dužina polja Šifra je 30 karaktera!'
			],
			'adresa' => [
				'max_length' => 'Maksimalna dužina polja Adresa je 30 karaktera!'
			],
			'grad' => [
				'max_length' => 'Maksimalna dužina polja Grad je 30 karaktera!'
			],
			'drzava' => [
				'max_length' => 'Maksimalna dužina polja Država je 30 karaktera!'
			],
			'postBroj' => [
				'max_length' => 'Maksimalna dužina polja Poštanski broj je 9 karaktera!',
				'numeric' => 'Poštanski broj sadrži samo cifre!'
			]
		])
		){
			$korisnik = $this->session->get("korisnik");
			$id = $korisnik->IdK;
			$data['ime'] = $korisnik->Ime;
			$data['prezime'] = $korisnik->Prezime;
			$data['imejl'] = $korisnik->Imejl;
			$data['grad'] = $korisnik->Grad;
			$data['sifra'] = $korisnik->Sifra;
			$data['adresa'] = $korisnik->Adresa;
			$data['drzava'] = $korisnik->Drzava;
			$data['postBroj'] = $korisnik->PostBroj;
			$data['rola'] = 'Korisnik';
			$data['errors'] =  $this->validator->getErrors();
			return $this->pozovi('nalog/nalog_izmena', $data);
		}

		$ime = $_POST['ime'];
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
			'Sifra'  => $sifra,
			'Adresa'  => $adresa,
			'Grad'  => $grad,
			'Drzava'  => $drzava,
			'PostBroj'  => $postBroj,
		];

		$id = $korisnik->IdK;
		$save = $korisnikModel->update($id, $data);

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
		$data['rola'] = 'Korisnik';
		$this->pozovi('nalog/nalog', $data);
	}
}
