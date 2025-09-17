<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConfigurationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_conf' => $this->id_conf,
            'cle' => $this->cle,
            'valeur' => $this->valeur,
            'categorie' => $this->categorie,
            'description' => $this->description,
            'type_donnee' => $this->type_donnee,
            'modifiable_interface' => $this->modifiable_interface,
            'cache_requis' => $this->cache_requis,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}