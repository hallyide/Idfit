<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\SubscriptionModel;

class AdminController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();

        $subscriptionModel =
            new SubscriptionModel();

        $data['total_users'] =
            $userModel->countAll();

        $data['total_gold'] =
            $userModel
            ->where('is_gold', 1)
            ->countAllResults();

        $data['total_subscriptions'] =
            $subscriptionModel->countAll();

        return view(
            'admin/dashboard',
            $data
        );
    }
        // 
        public function validerRecharge($idDemande) {
        $db = \Config\Database::connect();
        $db->transStart(); // Début de la transaction pour la sécurité

        // 1. Récupérer les infos de la demande
        $demande = $db->table('demandes_recharge')->getWhere(['id' => $idDemande])->getRow();

        if ($demande && $demande->statut == 0) {
            // Récupérer la valeur du code associé
            $code = $db->table('codes')->getWhere(['id' => $demande->code_id])->getRow();

            // 2. Ajouter l'argent à l'utilisateur (wallet_balance)
            $db->table('users')
            ->where('id', $demande->user_id)
            ->set('wallet_balance', "wallet_balance + {$code->valeur}", false)
            ->update();

            // 3. Marquer le code comme utilisé
            $db->table('codes')->where('id', $demande->code_id)->update([
                'is_used' => 1,
                'used_by' => $demande->user_id,
                'used_at' => date('Y-m-d H:i:s')
            ]);

            // 4. Marquer la demande comme validée
            $db->table('demandes_recharge')
            ->where('id', $idDemande)
            ->update([
                'statut' => 1,
                'valide_le' => date('Y-m-d H:i:s')
            ]);
            
            $db->transComplete();
            return redirect()->to(base_url('admin/validation-codes'))->with('message', 'Demande validée et compte crédité !');
        }

        $db->transRollback();
        return redirect()->to(base_url('admin/validation-codes'))->with('message', 'Erreur : Cette demande ne peut plus être traitée.');
    }
    // Affiche la liste des demandes de recharge en attente
    public function listeDemandes()
    {
        $model = new \App\Models\Demande_rechargeModel();
        // On utilise la fonction de jointure créée précédemment
        $data['demandes'] = $model->getPendingDemandes(); 

        return view('admin/validation_code', $data);
    }
}