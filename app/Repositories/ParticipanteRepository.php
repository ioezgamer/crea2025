<?php

namespace App\Repositories;

use App\Models\Participante;

class ParticipanteRepository
{
    protected Participante $model;

    public function __construct(Participante $model)
    {
        $this->model = $model;
    }

    /**
     * Obtener colección filtrada sin paginar
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function allFiltered(array $filters)
    {
        $query = $this->model->newQuery();

        if (!empty($filters['programa'])) {
            $query->where('programa', $filters['programa']);
        }
        if (!empty($filters['lugar_de_encuentro'])) {
            $query->where('lugar_de_encuentro_del_programa', $filters['lugar_de_encuentro']);
        }
        if (!empty($filters['grado'])) {
            $query->where('grado', $filters['grado']);
        }

        return $query;
    }

    /**
     * Crear participante
     */
    public function create(array $data): Participante
    {
        return $this->model->create($data);
    }

    /**
     * Buscar por ID
     */
    public function find(int $id): ?Participante
    {
        return $this->model->find($id);
    }

    /**
     * Actualizar participante
     */
    public function update(Participante $participante, array $data): bool
    {
        return $participante->update($data);
    }

    /**
     * Eliminar participante
     */
    public function delete(Participante $participante): bool
    {
        return $participante->delete();
    }
}
