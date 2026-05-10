<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\RegimeModel;
use App\Services\DashboardService;
use Dompdf\Dompdf;
use Dompdf\Options;

class PdfController extends BaseController
{
    public function downloadRapport()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'Veuillez vous connecter pour exporter le PDF.'
            ]);
        }

        // Utilisation du service existant pour récupérer toutes les données formatées
        $dashboardService = new DashboardService();
        $userData = $dashboardService->getDashboardData((int)$userId);

        // Récupération des détails du régime si un abonnement est actif
        $regime = null;
        if (!empty($userData['activeSubscription'])) {
            $regimeModel = new RegimeModel();
            $regime = $regimeModel->find($userData['activeSubscription']['regime_id']);
        }

        $data = [
            'title'       => 'Mon Rapport Santé IdFit',
            'date'        => date('d/m/Y'),
            'user'        => $userData,
            'regime'      => $regime,
            'imc'         => number_format($userData['imc'], 1),
            // Vous pouvez ajouter une logique pour la catégorie dans le service ou ici
            'categorie'   => $this->getImcCategorie($userData['imc']),
            'poids_ideal' => number_format($userData['idealWeight'], 1)
        ];

        $html = view('pdf/rapport', $data);

        // 3. Configuration de Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true); // Permet le chargement d'images et de CSS via URL
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // 4. Envoi au navigateur
        return $dompdf->stream("rapport_idfit.pdf", ["Attachment" => true]);
    }

    private function getImcCategorie(float $imc): string
    {
        if ($imc < 18.5) return "Insuffisance pondérale";
        if ($imc < 25) return "Poids normal";
        if ($imc < 30) return "Surpoids";
        return "Obésité";
    }
}