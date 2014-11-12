<?php

class TasksController extends \BaseController {

	/**
	 * Display a listing of medicals
	 *
	 * @return Response
	 */
	public function index()
	{
		Asset::push('js', 'app/tasks');
		Asset::push('css', 'tasks');
		$task_tags = Task__Tag::all()->map(function($tag){
            $tag->placement = 'left';
            $tag->order = 0;
            $placement = Task__TagUserPlacement::where('user_id', Auth::user()->id)
                ->where('tag_id', $tag->id)
                ->first();
            if($placement) {
                $tag->placement = $placement->name;
            }
            $order = Task__TagUserOrder::where('user_id', Auth::user()->id)
                ->where('tag_id', $tag->id)
                ->first();
            if($order) {
                $tag->order = $order->order;
            }
            return $tag;
        });
		$task_categories = Task__Category::all();
		return View::make('tasks.index', compact('task_tags', 'task_categories'));
	}

	/**
	 * Store a newly created medical in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$data = Input::all();
		$task = new Task__Main();
		$task->owner_id = Auth::user()->id;
		$task->description = $data['description'];
		$task->save();

		$follower = new Task__Follower();
		$follower->user_id = Auth::user()->id;
		$follower->todo_id = $task->id;
		$follower->save();

		$tags = Task__Tag::groupBy('tag_category_id')->whereNotIn('tag_category_id', [$data['category_id']])->lists('id');
		array_push($tags, $data['tag_id']);
		foreach ($tags as $tag_id) {
			Task__TodoTag::create([
				'tag_id' => $tag_id,
				'todo_id' => $task->id
			]);
		}

		foreach (Task__Category::all()->lists('id') as $category_id) {
			$order = new Task__Order();
			$order->todo_id = $task->id;
			$order->tag_category_id = $category_id;
			$order->user_id = Auth::user()->id;
			$order->save();
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        $task = Task__Main::findOrFail($id);
        $task->update(Input::all());
        $task->save();
        return $task;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Task__Main::destroy($id);
        return;
	}

	public function setTag($task_id, $tag_id)
	{
		$task = Task__Main::findOrFail($task_id);
		$tag_category_id = Task__Tag::findOrFail($tag_id)->tag_category_id;

		$tags = $task->tags->filter(function($tag) use ($tag_category_id) {
			return $tag->tag_category_id !== $tag_category_id;
		})->map(function($tag){
			return $tag->id;
		})->toArray();
		$tags[] = (int) $tag_id;
		$task->tags()->sync($tags);
	}

    public function streamTask()
    {
        $response = new Symfony\Component\HttpFoundation\StreamedResponse(function() {
            set_time_limit(660);
            $old_data = null;
            $start = time();
            $heartbeat = time();
            $user_id = Auth::user()->id;
            while (true) {
                $todo_ids = Task__Follower::where('user_id', $user_id)->lists('todo_id');
                if(count($todo_ids) > 0) {
                	$new_data = Task__Main::with('orders', 'owner', 'owner.profile', 'subtasks', 'followers', 'tags', 'tags.category')
	            		->whereIn('id', $todo_ids)
						->get()
						->toJson();
				} else {
					$new_data = '[]';
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

	public function setOrder($category_id)
	{
		$order = Input::get('order');
		foreach ($order as $index => $task_id) {
			$order = Task__Order::where('todo_id', $task_id)
				->where('tag_category_id', $category_id)
				->where('user_id', Auth::user()->id)
				->first();
			if(!$order) {
				$order = new Task__Order();
				$order->todo_id = $task_id;
				$order->tag_category_id = $category_id;
				$order->user_id = Auth::user()->id;
			}
			$order->order = $index;
			$order->save();
		}
		return $category_id;
	}

    function setArchived($task_id) {
        $task = Task__Main::findOrFail($task_id);
        $task->archived = 1;
        $task->save();
    }

    function unsetArchived($task_id) {
        $task = Task__Main::findOrFail($task_id);
        $task->archived = 0;
        $task->save();
    }

    public function addFollower($task_id, $user_id)
    {
        $task = Task__Main::findOrFail($task_id);
        $followers = $task->followers->lists('id');
        $followers[] = (int) $user_id;
        $followers = array_unique($followers);
        $followers = array_filter($followers);
        $task->followers()->sync($followers);
    }

    public function removeFollower($task_id, $user_id)
    {
        $task = Task__Main::findOrFail($task_id);
        $followers = $task->followers->lists('id');
        if(($key = array_search((int) $user_id, $followers)) !== false) {
            unset($followers[$key]);
        }
        $followers = array_filter($followers);
        $task->followers()->sync($followers);
    }

    public function setOwner($task_id, $user_id)
    {
        $task = Task__Main::findOrFail($task_id);
        $task->owner_id = $user_id;
        $task->save();
        $task->sendAssignedEmail();
        return $task;
    }

    public function notes($task_id) {
        return Task__Note::where('todo_id', $task_id)->orderBy('updated_at', 'DESC')->get();
    }

    public function newNote($task_id) {
        $note = new Task__Note();
        $note->todo_id = $task_id;
        $note->note = Input::get('note');
        $note->save();
        return $note;
    }

    public function updateNote($noteId) {
        $note = Task__Note::findOrFail($noteId);
        $note->note = Input::get('note');
        $note->save();
        return $note;
    }

    public function deleteNote($noteId) {
        $note = Task__Note::findOrFail($noteId);
        $note->delete();
        return $note;
    }

	public function __construct()
	{
		View::share('controller', 'Tasks');
	}

}