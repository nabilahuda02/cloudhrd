<?php

class AjaxController extends BaseController
{
    public function postUploadImage()
    {
        $file = Input::file('file');

        $uid = md5(microtime());
        $destinationPath = public_path() . '/uploads/profile/' . $uid;
        $parts = explode('/', $file->getMimeType());
        $extension = array_pop($parts);
        $filename = 'original.' . $extension;
        $upload_success = $file->move($destinationPath, $filename);

        Helper::resizeImage($destinationPath, $extension, 'avatar', 50);
        // Helper::resizeImage($destinationPath, $extension, 'thumbnail_' . $filename, 200);
        // Helper::resizeImage($destinationPath, $extension, 'medium_' . $filename, 600);

        $profile = Auth::user()->profile;
        $profile->user_image = '/uploads/profile/' . $uid . '/' . $filename;
        $profile->save();
        return $profile;
    }

    public function postUpdateShare()
    {
        $text = Input::get('text');
        $share_id = Input::get('id');
        $comment = new ShareComment();
        $comment->comment = $text;
        $comment->share_id = $share_id;
        $comment->user_id = Auth::user()->id;
        $comment->save();

        return View::make('wall.comment', compact('comment'));
    }

    public function getCommentCount($share_id)
    {
        return Share::find($share_id)->comments->count();
    }

    public function deleteComment($comment_id)
    {
        ShareComment::find($comment_id)->delete();
        return 'OK';
    }

    public function getUpcomming()
    {
        return Share::upcommingEvents();
    }

    public function postAvailableRooms()
    {
        $data = Input::all();
        $chosenSlots = $data['slots'];
        $date = $data['date'];

        $bookings = RoomBooking__Main::with('slots')->where('booking_date', $date)
            ->where('status_id', '<', 4)
            ->get();

        $bookingSlots = [];
        foreach ($bookings as $booking) {
            if (!isset($bookingSlots[$booking->room_booking_room_id])) {
                $bookingSlots[$booking->room_booking_room_id] = [];
            }

            $bookingSlots[$booking->room_booking_room_id][] = $booking->slots;
        }

        if (count($bookingSlots) == 0) {
            return RoomBooking__Room::all();
        }

        $excludes = [];
        foreach ($bookingSlots as $room_id => $_slots) {

            $slots = [];

            foreach ($_slots as $slot) {
                foreach ($slot as $s) {
                    $slots[] = $s;
                }
            }

            foreach ($slots as $slot) {
                if (in_array($slot->id, $chosenSlots)) {
                    $excludes[] = $room_id;
                }
            }
        }
        $excludes = array_unique($excludes);
        if (count($excludes) == 0) {
            return RoomBooking__Room::all();
        }

        return RoomBooking__Room::whereNotIn('id', $excludes)->get();
    }

    public function getBulletinPinned($set, $id)
    {
        if (!Auth::user()->is_admin) {
            return Response::Json('Forbidden', 403);
        }

        Share::findOrFail($id)->update(['pinned' => $set]);
        return 'OK';
    }

    public function getBulletinEmail($id)
    {
        $bulletin = Share::find($id);

        foreach (User::all() as $user) {
            Mail::send('emails.shares.' . $bulletin->type, compact('bulletin'), function ($message) use ($bulletin, $user) {
                $message->to($user->email, $user->profile->first_name . ' ' . $user->profile->last_name)->subject('Shared Item: ' . $bulletin->title);
            });
        }

        return $bulletin;
    }

    public function postQuickSearch()
    {
        $search = Input::get('search');

        $leave = Leave__Main::underMe()
            ->where('remarks', 'like', "%$search%")
            ->get()
            ->map(function ($leave) {
                return [
                    'status' => $leave->status_id,
                    'title' => $leave->ref,
                    'link' => '/leave/' . $leave->id,
                ];
            });
        $medical = MedicalClaim__Main::underMe()
            ->where('remarks', 'like', "%$search%")
            ->get()
            ->map(function ($medical) {
                return [
                    'status' => $medical->status_id,
                    'title' => $medical->ref,
                    'link' => '/medical/' . $medical->id,
                ];
            });
        $claims = GeneralClaim__Main::underMe()
            ->where('title', 'like', "%$search%")
            ->get()
            ->map(function ($claims) {
                return [
                    'status' => $claims->status_id,
                    'title' => $claims->ref,
                    'link' => '/claims/' . $claims->id,
                ];
            });
        return View::make('html.search', compact('leave', 'medical', 'claims'));
    }

    public function getEntitlement($module, $type, $user_id = null)
    {
        switch ($module) {
            case 'leave':
                return [
                    'colors' => Leave__Type::find($type)->getColors(),
                    'entitlement' => Leave__Type::find($type)->user_entitlement($user_id),
                    'utilized' => Leave__Type::find($type)->utilized_user_entitlement($user_id),
                    'balance' => Leave__Type::find($type)->user_entitlement_balance($user_id),
                ];
                break;
            case 'medical':
                return [
                    'colors' => MedicalClaim__Type::find($type)->getColors(),
                    'entitlement' => MedicalClaim__Type::find($type)->user_entitlement($user_id),
                    'utilized' => MedicalClaim__Type::find($type)->utilized_user_entitlement($user_id),
                    'balance' => MedicalClaim__Type::find($type)->user_entitlement_balance($user_id),
                ];
                break;
        }
    }

    public function getEntitlementBalances()
    {
        $response = [
            'leave' => [],
            'medical' => [],
        ];
        foreach (Leave__Type::all() as $leave) {
            $response['leave'][$leave->name] = $leave->user_entitlement_balance();
        }
        foreach (MedicalClaim__Type::all() as $medicalClaim) {
            $response['medical'][$medicalClaim->name] = $medicalClaim->user_entitlement_balance();
        }
        return $response;
    }

    public function getStatus($module)
    {

        switch ($module) {
            case 'leaves':
                $moduleId = 1;
                break;
            case 'medical_claims':
                $moduleId = 2;
                break;
            case 'general_claims':
                $moduleId = 3;
                break;
            case 'room_bookings':
                $moduleId = 4;
                break;
            default:
                $moduleId = 4;
                break;
        }

        return DB::table($module)
            ->select('status.name', DB::raw("count($module.id) as count"))
            ->whereIn("$module.user_id", Auth::user()->getDownline($moduleId, true))
            ->join('status', 'status.id', '=', "$module.status_id")
            ->groupBy("$module.status_id")
            ->get();
    }

    public function getUsers()
    {
        return User::with('profile')->get()->map(function ($user) {
            return [
                'value' => $user->id,
                'text' => $user->profile->first_name . ' ' . $user->profile->last_name,
            ];
        });
    }
}
