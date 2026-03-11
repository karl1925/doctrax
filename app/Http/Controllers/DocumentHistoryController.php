<?php

namespace App\Http\Controllers;

use App\Models\DocumentHistory;
use Illuminate\Http\Request;

class DocumentHistoryController extends Controller
{
    public static function log($userId, $documentId, $action, $remarks = null)
    {
        DocumentHistory::create([
            'user_id'     => $userId,
            'document_id' => $documentId,
            'action'      => $action,
            'remarks'     => $remarks,
        ]);
    }
}
