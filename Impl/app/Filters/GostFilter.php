<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\ModelRola;

class GostFilter implements FilterInterface
{
    public function before(RequestInterface $request)
    {
        $session = session();
        if ($session->has('korisnik')){
            $korisnik = $session->get('korisnik');
            $rolaModel = new ModelRola();
			$rola = $rolaModel->where('IdR', $korisnik->IdR)->first();
            return redirect()->to(site_url($rola->Opis));
        }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response)
    {
        // Do something here
    }
}