<?php

namespace App\Controllers;
use App\Models\ModelZahtevVer;

use App\Models\ModelKorisnik;

class Korisnik extends BaseController
{

	protected function pozovi($akcija)
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
		else return $this->pozovi('o_nama/o_nama_success');
	}

	
	public function zahtev_ver(){
		$this->pozovi('zahtev_ver/slanje_zahteva');
	}

	public function zahtev_ver_action(){
		///proveriti u filteru da li ima poslat podnet zahtev
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

			//Provera maksimalne veličine dokumenta - 16MB
			$limit = 16000000;
			if ($_FILES['zahtevFajl']['size'] >= $limit) {
				throw new \Exception('Prekoračena je maksimalna veličina fajla(16 MB)!');
			}


			$imefajla = $_FILES['zahtevFajl']['name'];
			$ekstenzija = pathinfo($imefajla, PATHINFO_EXTENSION);
			//Provera ekstenzije dokumenta - mora biti .pdf
			if ($ekstenzija !== 'pdf'){
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
		} catch (\Exception $th) {
			echo $th->getMessage();
		}

	}
}
