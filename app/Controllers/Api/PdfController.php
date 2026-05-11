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

        // Bloquer si pas de souscription active (demande faile.yaml)
        if (empty($userData['activeSubscription'])) {
            return $this->response->setStatusCode(403)->setJSON([
                'success' => false,
                'message' => 'Veuillez souscrire à un régime avant d\'exporter le rapport.'
            ]);
        }

        // Récupération du régime
        $regimeModel = new RegimeModel();
        $regime = $regimeModel->find($userData['activeSubscription']['regime_id']);

        // Récupération des sports liés au régime via la table de liaison
        $db = \Config\Database::connect();
        $sports = $db->table('sports s')
                     ->join('regime_sports rs', 'rs.sport_id = s.id')
                     ->where('rs.regime_id', $regime['id'])
                     ->get()->getResultArray();

        $data = [
            'title'       => 'Mon Rapport Santé IdFit',
            'date'        => date('d/m/Y'),
            'user'        => $userData,
            'regime'      => $regime,
            'sports'      => $sports,
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

        // Nettoyer le tampon de sortie pour éviter de corrompre le binaire
        if (ob_get_length()) ob_end_clean();
        
        $output = $dompdf->output();

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="rapport_idfit.pdf"')
            ->setBody($output);
    }

    private function getImcCategorie(float $imc): string
    {
        if ($imc < 18.5) return "Insuffisance pondérale";
        if ($imc < 25) return "Poids normal";
        if ($imc < 30) return "Surpoids";
        return "Obésité";
    }
}