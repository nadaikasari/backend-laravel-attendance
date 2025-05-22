<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PresenceRepositoryInterface;
use Illuminate\Support\Facades\Validator;

class PresenceController extends Controller
{
    protected $presenceRepository;

    public function __construct(PresenceRepositoryInterface $presenceRepository)
    {
        $this->presenceRepository = $presenceRepository;
    }

    public function generatePresence(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:IN,OUT',
            'date' => 'required|date_format:Y-m-d H:i:s'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'data'    => $validator->errors()
            ], 400);
        }
    
        $userId = $request->user()->id;
        $type = $request->input('type');
        $date = $request->input('date');
    
        $existPresence = $this->presenceRepository->existsPresence($userId, $type, $date);
    
        if ($existPresence) {
            return response()->json([
                'status' => false,
                'message' => 'Attendance with the same type and date already exists for this user',
                'data' => null
            ], 409);
        }
    
        $datas = [
            'user_id' => $userId,
            'type' => $type,
            'date' => $date,
        ];
    
        $result = $this->presenceRepository->createPresence($datas);
    
        return response()->json([
            'code'  => 201,
            'status'  => true,
            'message' => 'Attendance created successfully',
            'data'    => $result
        ], 201);
    }

    public function approvalPresence(Request $request, $presenceId)
    {
        $userSupervisorId = $request->user()->id;

        $userSupervisorNpp = $this->presenceRepository->getNppSupervisorUser($presenceId);
        $supervisorNpp = $this->presenceRepository->getNppUser($userSupervisorId);

        if($userSupervisorNpp !== $supervisorNpp) {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorized to approve this presence',
            ], 403);
        }

        $datas = [ 
            "is_approve"    => true,
            "approved_by"   => $userSupervisorId,
            "approval_date" => now()
        ];

        $result = $this->presenceRepository->updatePresence($datas, $presenceId);
        
        return response()->json([
            'status'  => true,
            'message' => 'Attendance approved successfully',
            'data'    => $result
        ], 201);
    }
    
    public function getAllDataPresence(Request $request)
    {
        $limit = $request->input('limit', 10);
        $page = $request->input('page', 1);  

        $result = $this->presenceRepository->getAllDataPresence($limit, $page);
        
        return response()->json([
            'code'  => 201,
            'status'  => true,
            'message' => 'Success get data',
            'data'    => $result
        ], 201);
    }
}
