<?php

namespace App\Repositories;

interface PresenceRepositoryInterface
{
    public function createPresence(array $data);
    public function existsPresence(int $userId, string $type, string $date);
    public function getNppSupervisorUser(int $presenceId);
    public function getNppUser(int $userId);
    public function updatePresence(array $data, int $presenceId);
    public function getAllDataPresence(int $limit, int $page);
}
