<?php

namespace App\Controllers;

use App\Models\ModelKorisnik;
use App\Models\ModelRola;
use App\Models\ModelPregled;

class Gost extends BaseController
{

	protected function pozovi($akcija, $data=[], $error_msg = "")
	{
		$data['controller'] = 'Gost';
		$data['error_msg'] = $error_msg;
		echo view('pocetna/header_gost.php', $data);
		echo view($akcija, $data);
		echo view('pocetna/footer.php', $data);
	}

	public function index()
	{
		$this->pozovi('pocetna/pocetna');
	}

	public function login()
	{
		$data['loginNeuspesan'] = '';
		$this->pozovi('login/login', $data);
	}

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
			$pregled = $pregledModel->where('IdPr!=', 1)->orderBy('IdPr', 'DESC')->findAll(1, 0);
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

	public function oporavak()
	{
		$this->pozovi('oporavak/oporavak');
	}

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

	public function registracija()
	{

		$this->pozovi('registracija/registracija');
	}

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
		$pregled = $pregledModel->where('IdPr!=', 1)->orderBy('IdPr', 'DESC')->findAll(1, 0);
		$pregledModel->update($pregled[0]->IdPr, ['BrLogovanja' => $pregled[0]->BrLogovanja + 1]);

		return redirect()->to(site_url('Korisnik'));
	}

	public function registracija_action()
	{
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
			return $this->pozovi('registracija/registracija_error', 'Niste uneli identičnu šifru potvrde, pokušajte ponovo.');
		}

		$provera = $korisnikModel->where("Imejl", $imejl)->first();

		if ($provera != null) {
			$_POST['imejl'] = "";
			return $this->pozovi('registracija/registracija_error', 'Već postoji korisnik sa identičnim imejlom. Molim vas da se unesete drugi imejl ili da se ulogujete na već postojeći profil. ');
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

	public function nalog()
	{
		$this->pozovi('nalog/nalog_onemogucen');
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
}
