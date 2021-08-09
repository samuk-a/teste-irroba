<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

use App\Models\Request as RequestAccess;
use App\Models\Classes;
use App\Models\User;
use Notification;
use Validator;
use App\Notifications\RequestNotification;

class NotificationController extends BaseController
{
    public function index() {
        $user = auth()->user();
        $notifications = clone $user->notifications;
        foreach ($notifications as $n)
            $n->markAsRead();
        return $user->notifications;
    }

    public function sendRequestNotification(Request $request) {
        $data = $request->all();
        $validator = Validator::make($data, [
            'request_id' => 'required|exists:requests,id',
            'accepted' => 'required|boolean',
            'reason_deny' => 'required_if:accepted,false',
            'site_url' => 'required|url'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $status = $data["accepted"] ? "ACEITA" : "NEGADA";
        $request = RequestAccess::where('id', $data['request_id'])->first()->toArray();
        $class = Classes::where('id', $request['class_id'])->first()->toArray();
        $user = auth()->user();
        $body = "Sua inscrição na disciplina {$class["name"]} foi $status.";
        if (!$data["accepted"]) $body .= " Pelo seguinte motivo: {$data["reason_deny"]}";
        $requestData = [
            'name' => $user->name,
            'body' => $body,
            'requestText' => $data["accepted"] ? 'Clique aqui para ir para a aula' : '',
            'requestUrl' => $data["accepted"] ? url("{$data["site_url"]}/{$data["request_id"]}") : '',
            'requestId' => $data['request_id']
        ];
        $user->notify(new RequestNotification($requestData));
    }
}
