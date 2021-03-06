<?php

class WallController extends \BaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {
        // Asset::push('js', 'wall');
        $user_image = Auth::user()->avatar();
        if (!$user_image) {
            $user_image = '/images/user.jpg';
        }
        return View::make('wall.index', compact('user_image', 'user'));
    }

    public function getFeeds($length = 10)
    {
        $user_id = Auth::user()->id;
        return Share::with('pins', 'user', 'user.profile')
            ->select('*', DB::raw("(select count(*) from user_share_pins where user_id = {$user_id} and share_id = shares.id) as is_pinned"))
            ->orderBy('is_pinned', 'desc')
            ->orderBy('updated_at', 'desc')
            ->take($length)
            ->get()
            ->toJson();
        return $response;
    }

    public function getFeed($id)
    {
        return Share::find($id)->load('pins', 'user', 'user.profile');
    }

    public function postFeed()
    {
        $data = Input::all();
        $data['user_id'] = Auth::user()->id;
        $share = Share::create($data);
        $share->save();
        app('pusher')->fire('wall', [
            'action' => 'created',
            'data' => $share->load('pins', 'user', 'user.profile'),
        ]);
        return $share;
    }

    public function putFeed($id)
    {
        $data = Input::all();
        $share = Share::find($id);
        $share->update(Input::only(['content']));
        app('pusher')->fire('wall', [
            'action' => 'updated',
            'data' => $share->load('pins', 'user', 'user.profile'),
        ]);
        return $share;
    }

    public function deleteFeed($id)
    {
        $share = Share::find($id);
        $share->delete();
        app('pusher')->fire('wall', [
            'action' => 'deleted',
            'id' => $id,
        ]);
        return 'ok';
    }

    public function getComments($length)
    {
        $response = new Symfony\Component\HttpFoundation\StreamedResponse(function () use ($length) {
            set_time_limit(660);
            $old_data = null;
            $start = time();
            $heartbeat = time();
            $user_id = Auth::user()->id;
            while (true) {
                $new_data = '[]';
                $ids = Share::select('*', DB::raw("(select count(*) from user_share_pins where user_id = {$user_id} and share_id = shares.id) as is_pinned"))
                    ->orderBy('is_pinned', 'desc')
                    ->orderBy('updated_at', 'desc')
                    ->take($length)
                    ->get()
                    ->lists('id');
                if (count($ids) > 0) {
                    $new_data = ShareComment::with('user', 'user.profile')
                        ->orderBy('created_at', 'desc')
                        ->whereIn('share_id', $ids)
                        ->get()
                        ->toJson();
                }
                if ($old_data !== $new_data) {
                    $old_data = $new_data;
                    echo 'data: ' . $new_data . "\n\n";
                    ob_flush();
                    flush();
                } else if (time() - $heartbeat > 50) {
                    $heartbeat = time();
                    echo 'id: ' . uniqid() . "\n\n";
                    ob_flush();
                    flush();
                }
                if (time() - $start > 600) {
                    exit(0);
                }

                sleep(1);
            }
        });
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('X-Accel-Buffering', 'no');
        return $response;
    }

    public function postCreateComment($share_id)
    {
        if ($share = Share::find($share_id)) {
            $data = Input::all();
            $data['user_id'] = Auth::user()->id;
            $data['share_id'] = $share_id;
            $comment = ShareComment::create($data);
            $comment->save();
            return $comment;
        }
        return;
    }

    public function getRemoveShare($id)
    {
        if ($share = Share::find($id)) {
            if ($share->user_id === Auth::user()->id || Auth::user()->is_admin) {
                ShareComment::where('share_id', $id)->delete();
                $share->delete();
            }
        }
    }

    public function getRemoveComment($id)
    {
        if ($comment = ShareComment::find($id)) {
            if ($comment->user_id === Auth::user()->id || Auth::user()->is_admin) {
                $comment->delete();
            }
        }
    }

    public function getSetPin($share_id)
    {
        if ($share = Share::find($share_id)) {
            $pin = new UserSharePin();
            $pin->share_id = $share_id;
            $pin->user_id = Auth::user()->id;
            $pin->save();
        }
        return;
    }

    public function getUnsetPin($share_id)
    {
        if ($share = Share::find($share_id)) {
            UserSharePin::where('user_id', Auth::user()->id)
                ->where('share_id', $share_id)
                ->delete();
        }
        return;
    }

    public function getProfile()
    {
        $currentuser = Auth::user();
        return View::make('profiles.form', compact('currentuser'));
    }

    public function getChangePassword()
    {
        $currentuser = Auth::user();
        return View::make('wall.change-password', compact('currentuser'));
    }

    public function postChangePassword()
    {
        $user = Auth::user();
        $validator = Validator::make($data = Input::all(), User::$validation_rules['change_own_password']);
        if ($validator->fails()) {
            return Redirect::back()
                ->withErrors($validator)
                ->withInput()
                ->with('NotifyDanger', 'Validation error');
        }
        if (!Hash::check($data['old_password'], $user->password)) {
            return Redirect::back()
                ->with('NotifyDanger', 'Invalid old password');
        }
        $user->password = Hash::make($data['password']);
        $user->save();
        if ($user->id === 1) {
            app()->master_user->password = $user->password;
            app()->master_user->save();
        }
        Auth::logout();
        Cache::flush();
        Session::flash('NotifySuccess', 'Password successfully updated');
        return Redirect::action('AuthController@getLogin');
    }

    public function postFeedback()
    {
        /**
         * Datetime
         * User Reporter
         * User Details
         * Organization Details
         */
        $user = Auth::user();
        $report = ['issue' => '', 'image' => ''];
        $data = Input::get('data');
        $data = json_decode($data);
        $report['issue'] = $data[0]->Issue;
        if (isset($data[1])) {
            $report['image'] = $data[1];
        }
        $feedback = new Master__Feedback();
        $feedback->user_id = $_ENV['cloudhrd']->id;
        $feedback->description = $data[0]->Issue;
        $feedback->server_params = json_encode($_SERVER);
        $feedback->session_params = json_encode(Session::all());
        $feedback->user_details = json_encode(Auth::user());
        $feedback->screenshot = json_encode($data[1]);
        $feedback->save();

        Mail::send(['html' => 'emails.support'], compact('user', 'report'), function ($message) use ($report) {
            $message->to('support@cloudhrd.com', 'CloudHRD Support')
                ->replyTo(Auth::user()->email)
                ->subject('Support request: ' . substr($report['issue'], 0, 30));
        });
    }

    public function __construct()
    {
        View::share('controller', 'Public Wall');
    }

}
