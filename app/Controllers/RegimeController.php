<?php

namespace App\Controllers;

use App\Models\RegimeModel;

class RegimeController extends BaseController
{
   public function index()
    {
        $model = new RegimeModel();

        // 1. Récupérer tous les régimes
        // Note : Idéalement, crée une méthode dans ton Model pour joindre les prix et les sports
        $regimes = $model->findAll();

        // 2. Préparer les données pour la vue
        $data = [
            'regimes'       => $regimes,
            'isGold'        => session()->get('is_gold') ?? false,
            'walletBalance' => session()->get('wallet_balance') ?? 0,
            'userFirstName' => session()->get('prenom') ?? 'Utilisateur',
            'userLastName'  => session()->get('nom') ?? '',
            'title'         => 'Régimes Disponibles'
        ];

        // 3. Charger la vue (Assure-toi que le chemin correspond à ton fichier)
        return view('admin/regimes/index', $data);
    }
    public function create()
    {
        $model = new RegimeModel();

        $model->save([
            'nom' => $this->request->getPost('nom'),
            'description' => $this->request->getPost('description'),
            'objectif' => $this->request->getPost('objectif'),
            'pct_viande' => $this->request->getPost('pct_viande'),
            'pct_poisson' => $this->request->getPost('pct_poisson'),
            'pct_volaille' => $this->request->getPost('pct_volaille'),
            'calories_jour' => $this->request->getPost('calories_jour'),
            'duree_moyenne' => $this->request->getPost('duree_moyenne')
        ]);

        return redirect()->back();
    }

    public function delete($id)
    {
        $model = new RegimeModel();

        $model->delete($id);

        return redirect()->back();
    }
}
