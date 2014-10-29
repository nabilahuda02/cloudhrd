<?php

class WallController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
        Asset::push('js','app/wall.js');
		$user_image = Auth::user()->profile->user_image;
		return View::make('wall.index',compact('user_image', 'user'));
	}

    public function getShares($length)
    {
        $response = new Symfony\Component\HttpFoundation\StreamedResponse(function() use ($length) {
            set_time_limit(660);
            $old_data = null;
            $start = time();
            $heartbeat = time();
            $user_id = Auth::user()->id;
            while (true) {
                $new_data = Share::with('pins', 'user', 'user.profile')
                    ->select('*', DB::raw("(select count(*) from user_share_pins where user_id = {$user_id} and share_id = shares.id) as is_pinned"))
                    ->orderBy('is_pinned', 'desc')
                    ->orderBy('updated_at', 'desc')
                    ->take($length)
                    ->get()
                    ->toJson();
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
                if(time() - $start > 600)
                    exit(0);
                sleep(1);
            }
        });
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('X-Accel-Buffering', 'no');
        return $response;
    }

	public function getComments($length)
	{
        $response = new Symfony\Component\HttpFoundation\StreamedResponse(function() use ($length) {
        	set_time_limit(660);
        	$old_data = null;
        	$start = time();
        	$heartbeat = time();
            $user_id = Auth::user()->id;
            while (true) {
                $new_data = '[]';
                $ids = Share::select('*', DB::raw("(select count(*) from user_share_pins where user_id = {$user_id} and share_id = shares.id) as is_pinned"))
                    ->orderBy('is_pinned', 'desc')
                    ->orderBy('updated_at','desc')
                    ->take($length)
                    ->get()
                    ->lists('id');
                if(count($ids) > 0) {
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
                if(time() - $start > 600)
                	exit(0);
                sleep(1);
            }
        });
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('X-Accel-Buffering', 'no');
        return $response;
	}

	public function postCreateShare()
	{
		$data = Input::all();
		$data['user_id'] = Auth::user()->id;
		$share = Share::create($data);
		$share->save();
		return $share;
	}

	public function postCreateComment($share_id)
	{
		if($share = Share::find($share_id)) {
			$data = Input::all();
			$data['user_id'] = Auth::user()->id;
			$data['share_id'] = $share_id;
			$comment = ShareComment::create($data);
			$comment->save();
			// $share->touch();
			return $comment;
		}
		return;
	}

	public function getRemoveShare($id)
	{
		if($share = Share::find($id)) {
			if($share->user_id === Auth::user()->id) {
				ShareComment::where('share_id', $id)->delete();
				$share->delete();
			}
		}
	}

	public function getRemoveComment($id)
	{
		if($comment = ShareComment::find($id)) {
			if($comment->user_id === Auth::user()->id) {
				$comment->delete();
			}
		}
	}

    public function getSetPin($share_id)
    {
        if($share = Share::find($share_id)) {
            $pin = new UserSharePin();
            $pin->share_id = $share_id;
            $pin->user_id = Auth::user()->id;
            $pin->save();
        }
        return;
    }

    public function getUnsetPin($share_id)
    {
        if($share = Share::find($share_id)) {
            UserSharePin::where('user_id', Auth::user()->id)
                ->where('share_id', $share_id)
                ->delete();
        }
        return;
    }


	public function __construct()
	{
		View::share('controller', 'Public Wall');
	}

}