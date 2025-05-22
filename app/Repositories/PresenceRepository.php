<?php

namespace App\Repositories;

use App\Models\Presence;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PresenceRepository implements PresenceRepositoryInterface
{
    public function createPresence(array $data)
    {
        return Presence::create([
            'user_id'         => $data['user_id'],
            'type'            => $data['type'],
            'date'            => $data['date'],
        ]);
    }

    public function existsPresence(int $userId, string $type, string $date): bool
    {
        return Presence::where('user_id', $userId)
            ->where('type', $type)
            ->whereDate('date', $date)
            ->exists();
    }

    public function getNppSupervisorUser(int $presenceId)
    {
        $presence = Presence::with('user')->find($presenceId);
        return $presence?->user?->npp_supervisor;
    }

    public function getNppUser(int $userId)
    {
        return User::where('id', $userId)->value('npp');
    }

    public function updatePresence(array $data, int $presenceId)
    {
        $presence = Presence::find($presenceId);

        if (!$presence) {
            return null; 
        }

        return $presence->update($data);
    }

    public function getAllDataPresence(int $limit, int $page)
    {
        $offset = ($page - 1) * $limit;

        return DB::table('presences')
            ->select(
                'users.id as user_id',
                'users.name as nama_user',
                DB::raw('DATE(presences.date) as tanggal'),
                DB::raw("TO_CHAR(MAX(CASE WHEN type = 'IN' THEN presences.date END), 'HH24:MI:SS') as waktu_masuk"),
                DB::raw("TO_CHAR(MAX(CASE WHEN type = 'OUT' THEN presences.date END), 'HH24:MI:SS') as waktu_pulang"),                
                DB::raw("
                    CASE
                        WHEN bool_or(CASE WHEN type = 'IN' THEN presences.is_approve ELSE false END) THEN 'approved'
                        ELSE 'rejected'
                    END as status_masuk
                "),
                DB::raw("
                    CASE
                        WHEN bool_or(CASE WHEN type = 'OUT' THEN presences.is_approve ELSE false END) THEN 'approved'
                        ELSE 'rejected'
                    END as status_pulang
                ")                                      
            )
            ->join('users', 'users.id', '=', 'presences.user_id')
            ->groupBy('users.id', 'users.name', DB::raw('DATE(presences.date)'))
            ->limit($limit)
            ->offset($offset)
            ->get();
    }
}
