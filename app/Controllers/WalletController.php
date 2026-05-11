<?php

namespace App\Controllers;

use App\Models\CodeModel;
use App\Models\UserModel;
use App\Models\RechargeHistoryModel;
use App\Models\Demande_rechargeModel;

class WalletController extends BaseController
{
    public function recharge()
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'Utilisateur non connecte'
            ]);
        }

        $codeValue = $this->request->getPost('code');

        $codeModel = new CodeModel();
        $demandeModel = new Demande_rechargeModel();

        $code = $codeModel
            ->where('code', $codeValue)
            ->where('is_used', 0)
            ->first();

        if (!$code) {

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Code invalide'
            ]);
        }

        // Vérifier si une demande identique est déjà en attente pour ce code
        $existing = $demandeModel->where('code_id', $code['id'])
                                 ->where('statut', 0)
                                 ->first();
        if ($existing) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Cette demande est déjà en cours de validation.'
            ]);
        }

        // Au lieu de créditer, on crée la demande
        $demandeModel->save([
            'user_id' => $userId,
            'code_id' => $code['id'],
            'statut'  => 0 // En attente
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Demande envoyée, en attente de validation admin.'
        ]);
    }
}