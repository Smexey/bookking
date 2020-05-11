<?php namespace App\Controllers;

use App\Models\ModelKorisnik;

class Admin extends BaseController
{

	protected function pozovi($akcija){
		$data['controller']='Admin';
		echo view('pocetna/header_admin.php',$data);
		echo view($akcija, $data);	
		echo view('pocetna/footer.php',$data);
	}

	public function index() {	
		$this->pozovi('pocetna/pocetna');
	}

	public function logout(){
		$this->pozovi('login/logout');
	}
	
    public function logout_action(){
        return redirect()->to(site_url('Gost'));
	}
	

	public function o_nama(){
		$this->pozovi('o_nama/o_nama');	
	}


	public function o_nama_action(){
		$imejl = $_POST['imejl'];
		$poruka = $_POST['poruka'];
		
		if($imejl=="") return $this->pozovi('o_nama/o_nama_error');
		else return $this->pozovi('o_nama/o_nama_success');
	}
}