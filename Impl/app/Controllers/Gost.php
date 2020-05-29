<?php

namespace App\Controllers;

use App\Models\ModelKorisnik;
use App\Models\ModelRola;
use App\Models\ModelPregled;

/**
* Gost – klasa koja predstavlja rolu gosta tj. korisnika pre nego sto se loginuje
*
* @version 1.0
*/
class Gost extends BaseController
{

	/**
	* Funkcija koju ostale funkcije pozivaju zbog ucitavanja odgovarajuce stranice
	*
	* @param String $akcija
	* @param String[] $data
	* @return void
	*/
	protected function pozovi($akcija, $data=[])
	{
		$data['controller'] = 'Gost';
		echo view('pocetna/header_gost.php', $data);
		echo view($akcija, $data);
		echo view('pocetna/footer.php', $data);
	}

	/**
	* Funkcija koju kontoler poziva za ucitavanje pocetne stranice 
	*
	* @return void
 	*/
	public function index()
	{
		$this->pozovi('pocetna/pocetna');
	}

	/**
	* Funkcija koju kontoler poziva za ucitavanje login stranice 
	*
	* @return void
 	*/
	public function login()
	{
		$data['loginNeuspesan'] = '';
		$this->pozovi('login/login', $data);
	}

	/**
	* Funkcija koju kontoler poziva za proveru podatak pri pritisku dugmeta u login formi 
	* Funkcija proverava da li postoji korisnik sa zadatim imejlom i sifrom i preusmerava ga
	*
	* @return void
 	*/
	public function login_action()
	{
		$imejl = $_POST['imejl'];
		$sifra = $_POST['sifra'];

		$korisnikModel = new ModelKorisnik();
		$korisnik = $korisnikModel->where(['Imejl' => $imejl, 'Stanje' => 'Vazeci'])->where("Sifra", $sifra)->first();

		if ($korisnik != null) {
			$rolaModel = new ModelRola();
			$rola = $rolaModel->where('IdR', $korisnik->IdR)->first();

			$pregledModel = new ModelPregled();
			$pregled = $pregledModel->orderBy('IdPr', 'DESC')->findAll(1, 0);
			$pregledModel->update($pregled[0]->IdPr, ['BrLogovanja' => $pregled[0]->BrLogovanja + 1]);

			$this->session->set('korisnik', $korisnik);


			if ($rola->Opis == "Korisnik") return redirect()->to(site_url('Korisnik'));
			else if ($rola->Opis == "Admin") return redirect()->to(site_url('Admin'));
			else if ($rola->Opis == "Verifikovani") return redirect()->to(site_url('Verifikovani'));
			else if ($rola->Opis == "Moderator") return redirect()->to(site_url('Moderator'));

			return redirect()->to(site_url('Gost'));
		} 
		else {
			$data['loginNeuspesan'] = 'Login nije uspeo!';
			return $this->pozovi('login/login', $data);
		}
	}

	/**
	* Funkcija koju kontoler poziva za ucitavanje stranice za oporavak 
	*
	* @return void
 	*/
	public function oporavak()
	{
		$this->pozovi('oporavak/oporavak');
	}


	/**
	* Funkcija koju kontoler poziva prilikom pritiska na dugme na stranici za oporavak  
	* Funkcija salje mejl u slucaju da je username dobro unet u suprotnom ispisuje poruku o gresci
	*
	* @return void
 	*/
	public function oporavak_action()
	{
		$imejl = $_POST["imejl"];

		$korisnikModel = new ModelKorisnik();
		$korisnik = $korisnikModel->where(['Imejl' => $imejl, 'Stanje' => 'Vazeci'])->first();

		if ($korisnik != null) {
			$message = "Zdravo " . $korisnik->Ime . ",";
			$message .= "\n\nOvaj mejl je poslat na zahtev oporavka šifre sa sajta bookking.com. U slučaju da ne prepoznajete ovu akciju molimo Vas da pošaljete mejl na našu adresu.";
			$message .= "\nŠifra: " . $korisnik->Sifra;

			$email = \Config\Services::email();

			$email->setFrom('bookkingPSI@gmail.com', 'Bookking');
			$email->setTo($imejl);

			$email->setSubject('Oporavak šifre');
			$email->setMessage($message);

			$result = $email->send();
			//$result = mail($imejl, "Be king, bookking", $message, "From: bookkingPSI@gmail.com");

			if ($result) $this->pozovi('oporavak/oporavak_success');
			else $this->pozovi('oporavak/oporavak_error');
		} else $this->pozovi('oporavak/oporavak_error');
	}

	/**
	* Funkcija koju kontoler poziva za ucitavanje stranice za registraciju 
	*
	* @return void
 	*/
	public function registracija()
	{

		$this->pozovi('registracija/registracija');
	}
	
	/**
	* Funkcija koju kontoler poziva prilikom sumbita sifra pri slanju zahteva za registraciju 
	*
	* @return void
 	*/
	public function registracija_confirme_action()
	{
		$ime = $_POST['ime'];
		$imejl = $_POST['imejl'];
		$sifra = $_POST['sifra'];
		$prezime = $_POST['prezime'];
		$adresa = $_POST['adresa'];
		$grad = $_POST['grad'];
		$drzava = $_POST['drzava'];
		$postBroj = $_POST['postBroj'];
		$mojKod = $_POST['confirmeCode'];
		$idR = 0;
		$kod = $_POST['kod'];

		$korisnikModel = new ModelKorisnik();
		$korisnik = new Korisnik();


		if ($kod != $mojKod) {
			$_POST['kod'] = "";
			return $this->pozovi('registracija/registracija');
		}


		$maxId = -1;
		$korisnici = $korisnikModel->findAll();
		foreach ($korisnici as $k) {
			if ($k->IdK > $maxId) $maxId = $k->IdK;
		}

		$korisnikModel->save([
			'Imejl'  => $imejl,
			'Sifra'  => $sifra,
			'Ime'  => $ime,
			'Prezime'  => $prezime,
			'Adresa'  => $adresa,
			'Grad'  => $grad,
			'Drzava'  => $drzava,
			'PostBroj'  => $postBroj,
			'Stanje' => 'Vazeci',
			'IdR'  => 1,
		]);
		
		$korisnik = $korisnikModel->find($maxId + 1);
		$this->session->set('korisnik', $korisnik);

		$pregledModel = new ModelPregled();
		$pregled = $pregledModel->orderBy('IdPr', 'DESC')->findAll(1, 0);
		$pregledModel->update($pregled[0]->IdPr, ['BrLogovanja' => $pregled[0]->BrLogovanja + 1]);

		return redirect()->to(site_url('Korisnik'));
	}

	/**
	* Funkcija koju kontoler poziva pri submitu podataka sa stranice za registraciju 
	* Funkcija proverava ulazne podatke ispitujuci ogranicenja
	* Salje kod za potvrdu na mejl
	*
	* @return void
 	*/
	public function registracija_action()
	{
		
		if (!$this->validate([
			'imejl' => 'required',
			'sifra' =>  'required',
			'sifraPonovo' =>  'required',
			'ime' => 'required',
			'prezime' => 'required',
			'adresa' => 'required',
			'grad' => 'required',
			'drzava' => 'required',
			'postBroj' => 'required',
		])
		){
			return $this->pozovi('registracija/registracija', ['errors' => ['Sva polja su obavezna!']]);
		}
		else if (!$this->validate([
			'ime' => 'max_length[30]',
			'prezime' => 'max_length[30]',
			'imejl' => 'max_length[50]',
			'sifra' =>  'max_length[30]',
			'sifraPonovo' =>  'max_length[30]',
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
			'imejl' => [
				'max_length' => 'Maksimalna dužina polja Imejl je 50 karaktera!'
			],
			'sifra' => [
				'max_length' => 'Maksimalna dužina polja Šifra je 30 karaktera!'
			],
			'sifraPonovo' => [
				'max_length' => 'Maksimalna dužina polja Potvrdi Šifru je 30 karaktera!'
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
			return $this->pozovi('registracija/registracija', ['errors' => $this->validator->getErrors()]);
		}
		$ime = $_POST['ime'];
		$imejl = $_POST['imejl'];
		$sifra = $_POST['sifra'];
		$prezime = $_POST['prezime'];
		$adresa = $_POST['adresa'];
		$grad = $_POST['grad'];
		$drzava = $_POST['drzava'];
		$postBroj = $_POST['postBroj'];
		$idR = 0;

		$sifra2 = $_POST['sifraPonovo'];
		$korisnikModel = new ModelKorisnik();
		$korisnik = new Korisnik();
		
		if ($sifra != $sifra2) {
			$_POST['sifra'] = "";
			$_POST['sifraPonovo'] = "";
			return $this->pozovi('registracija/registracija', ['errors' => ['Niste uneli identičnu šifru potvrde, pokušajte ponovo.']]);
		}

		$provera = $korisnikModel->where("Imejl", $imejl)->first();

		if ($provera != null) {
			$_POST['imejl'] = "";
			return $this->pozovi('registracija/registracija', ['errors' => ['Već postoji korisnik sa identičnim imejlom. Molim vas da se unesete drugi imejl ili da se ulogujete na već postojeći profil.']]);
		}


		$maxId = -1;
		$korisnici = $korisnikModel->findAll();
		foreach ($korisnici as $k) {
			if ($k->IdK > $maxId) $maxId = $k->IdK;
		}

		$code = "";

		for ($i = 0; $i < 6; $i++) {
			$option = rand(0, 2);
			if ($option == 0) $code .= chr(rand(48, 57));
			else if ($option == 1) $code .= chr(rand(65, 90));
			else $code .= chr(rand(97, 122));
		}

		$message = "Zdravo " . $ime . ",";
		$message .= "\n\nOvaj mejl je poslat na zahtev registracije naloga na sajta bookking.com. Kod koji je potrebno da potvrdite nalazi se na dnu mejla.";
		$message .= "\nKod: " . $code;

		
		$email = \Config\Services::email();

		$email->setFrom('bookkingPSI@gmail.com', 'Bookking');
		$email->setTo($imejl);

		$email->setSubject('Registracija naloga');
		$email->setMessage($message);

		$result = $email->send();

		//$result = mail($imejl, "Be king, bookking", $message, "From: bookkingPSI@gmail.com");

		$_POST['confirmeCode'] = $code;
		$this->pozovi('registracija/registracija_confirme');
	}

	/**
	* Funkcija koju kontoler poziva za ucitavanje nalog stranice 
	*
	* @return void
 	*/
	public function nalog()
	{
		$this->pozovi('nalog/nalog_onemogucen');
	}

	/**
	* Funkcija koju kontoler poziva za ucitavanje o_nama stranice 
	*
	* @return void
 	*/
	public function o_nama()
	{
		$this->pozovi('o_nama/o_nama');
	}

	/**
	* Funkcija koju kontoler poziva pri pritisku dugmeta na stranici o nama 
	* Salje mejl sa porukom na adresu bookkingPSI@gmail.com
	*
	* @return void
 	*/
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
}
