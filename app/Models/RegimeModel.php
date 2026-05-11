<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeModel extends Model
{
    protected $table = 'regimes';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nom',
        'description',
        'objectif',
        'pct_viande',
        'pct_poisson',
        'pct_volaille',
        'calories_jour',
        'duree_moyenne'
    ];
    public function getRegimesWithPrice()
    {
        return $this->select('regimes.*, prix_regime.prix as prix_base')
                    ->join('prix_regime', 'prix_regime.regime_id = regimes.id', 'left')
                    ->groupBy('regimes.id')
                    ->findAll();
    }
}