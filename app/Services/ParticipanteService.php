<?php

namespace App\Services;

use App\Models\Participante;
use App\Repositories\ParticipanteRepository;
use Illuminate\Database\DatabaseManager;

class ParticipanteService
{
    protected ParticipanteRepository $repo;
    protected DatabaseManager $db;

    public function __construct(ParticipanteRepository $repo, DatabaseManager $db)
    {
        $this->repo = $repo;
        $this->db = $db;
    }

    /**
     * Lista con filtros y paginación
     */
    public function list(array $filters, int $perPage = 15)
    {
        $query = $this->repo->allFiltered($filters);
        return $query->orderBy('created_at', 'desc')
                     ->paginate($perPage)
                     ->withQueryString();
    }

    /**
     * Crear participante (transacción)
     */
    public function create(array $data): Participante
    {
        return $this->db->transaction(function() use ($data) {
            return $this->repo->create($data);
        });
    }

    /**
     * Actualizar participante (transacción)
     */
    public function update(Participante $participante, array $data): bool
    {
        return $this->db->transaction(function() use ($participante, $data) {
            return $this->repo->update($participante, $data);
        });
    }

    /**
     * Eliminar participante
     */
    public function delete(Participante $participante): bool
    {
        return $this->repo->delete($participante);
    }
}
