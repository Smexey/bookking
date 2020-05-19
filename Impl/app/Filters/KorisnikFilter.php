<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\ModelKorisnik;
use App\Models\ModelRola;

class KorisnikFilter implements FilterInterface
{
    public function before(RequestInterface $request)
    {
        $session = session();
        if ($session->has('korisnik')){
            $korisnik = $session->get('korisnik');
            $korisnikModel = new ModelKorisnik();
            $korisnikUBazi = $korisnikModel->find($korisnik->IdK);
            if ($korisnikUBazi == null || $korisnikUBazi->Stanje != 'Vazeci'){
                $session->destroy();
                return redirect()->to(site_url('Gost'));
            }
            $rolaModel = new ModelRola();
            $rola = $rolaModel->where('IdR', $korisnik->IdR)->first();
            if ($rola->Opis != 'Korisnik')
                return redirect()->to(site_url($rola->Opis));
        }
        else{
            return redirect()->to(site_url('Gost'));
        }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response)
    {
        // Do something here
    }
}