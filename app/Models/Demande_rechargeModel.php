<?php

namespace App\Models;

use CodeIgniter\Model;

class Demande_rechargeModel extends Model
{
    protected $table            = 'demandes_recharge';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // Champs que l'on autorise à être modifiés
    protected $allowedFields    = ['user_id', 'code_id', 'statut', 'valide_le'];

    // Gestion automatique des dates
    protected $useTimestamps = true;
    protected $createdField  = 'date_demande';
    protected $updatedField  = ''; // On ne s'en sert pas ici

    /**
     * Récupère l'historique des recharges pour un utilisateur précis
     * avec le montant et le texte du code (Jointure)
     */
    public function getHistoryWithDetails($userId)
    {
        return $this->select('demandes_recharge.*, codes.code as code_label, codes.valeur as montant')
                    ->join('codes', 'codes.id = demandes_recharge.code_id')
                    ->where('demandes_recharge.user_id', $userId)
                    ->orderBy('demandes_recharge.date_demande', 'DESC')
                    ->findAll();
    }

    /**
     * Récupère toutes les demandes en attente pour l'interface Admin
     */
    public function getPendingDemandes()
    {
        return $this->select('demandes_recharge.*, users.nom, users.prenom, codes.code, codes.valeur')
                    ->join('users', 'users.id = demandes_recharge.user_id')
                    ->join('codes', 'codes.id = demandes_recharge.code_id')
                    ->where('demandes_recharge.statut', 0)
                    ->findAll();
    }
}