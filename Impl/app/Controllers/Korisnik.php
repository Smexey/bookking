<?php

namespace App\Controllers;
use App\Models\ModelZahtevVer;
use App\Models\ModelKorisnik;
use App\Models\ModelOglas;
use App\Models\ModelOglasTag;
use App\Models\ModelStanje;
use App\Models\ModelTag;

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
		else return $this->pozovi('o_nama/o_nama_success');
	}

	
	public function zahtev_ver(){
		$zahtevVerModel = new ModelZahtevVer();
		$korisnik = $this->session->get("korisnik");
		//Provera da li postoji neobradjen zahtev trebutnog korisnika
		if ($zahtevVerModel->proveraZahtevPodnet($korisnik->IdK)){
			return $this->pozovi('zahtev_ver/slanje_zahteva_podnet');
		}
		else {
			$data['zahtevNeuspesan'] = '';
			return $this->pozovi('zahtev_ver/slanje_zahteva', $data);
		}
	}

	public function zahtev_ver_action(){
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
			//Ispis u slucaju da je zahtev uspesno poslat
			return $this->pozovi('zahtev_ver/slanje_zahteva_success');
		} catch (\Exception $th) {
			//Ispis greske
			$data['zahtevNeuspesan'] = $th->getMessage();
			return $this->pozovi('zahtev_ver/slanje_zahteva', $data);
		}

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

	//Rade
	public function dodaj_oglas(){
		$this->pozovi('pretraga/dodajOglas');
	}

	//Rade
	public function nova_vest(){
		if(!$this->validate([ 
							'naslov'=>'required|min_length[2]|max_length[50]',
							'opis'=>'required|min_length[5]',
							'autor'=>'required|min_length[5]',
							'cena'=>'required|numeric']))
			return $this->pozovi('pretraga/dodajOglas',
				['errors'=>$this->validator->listErrors()]);
		$stanjeModel = new ModelStanje();
		$stanje = $stanjeModel->where(['Opis'=>'Okacen'])->first();
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
		$tags = $this->request->getVar('tags');
		// $tags = strtolower($tags);
		$tags = preg_split("/[\s,]+/", $tags);
		// $tags = preg_match("/[\w]+/",$tags);
		$tagModel = new ModelTag();
		$oglasTagModel = new ModelOglasTag();
		foreach ($tags as $tag) { 
			$tagModel->save([ 
				'Opis' => $tag 
			]); 
			// $tagId = $tagModel->where(['Opis'=>$tag])->first();
			$tagId = $tagModel->getInsertID();
			$oglasTagModel->save([ 
				'IdT' => $tagId,
				'IdO' => $lastOglasID 
			]);
		}
		return redirect()->to(site_url("Korisnik/oglas/{$lastOglasID}"));
	}
	
}
