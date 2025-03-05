<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\TopicRequest;
use App\Models\Category;
use App\Models\Link;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TopicsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Topic $topic, User $user, Link $link)
    {
        $topics = $topic->withOrder($request->order)->with('user', 'category')->paginate(20);
        $active_users = $user->getActiveUsers();
        $links = $link->getAllCached();

        return view('topics.index', compact('topics', 'active_users', 'links'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Topic $topic)
    {
        $categories = Category::all();
        return view('topics.create_and_edit', compact('topic', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TopicRequest $request, Topic $topic)
    {
        $topic->fill($request->all());
        $topic->user_id = Auth::id();
        $topic->save();

        return redirect()->to($topic->link())->with('success', '帖子创建成功！');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Topic $topic)
    {
        if (!empty($topic->slug) && $topic->slug != $request->slug) {
            return redirect($topic->link(), 301);
        }
        return view('topics.show', compact('topic'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Topic $topic)
    {
        $this->authorize('update', $topic);
        $categories = Category::all();
        return view('topics.create_and_edit', compact('topic', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Topic $topic)
    {
        $this->authorize('update', $topic);
        $topic->update($request->all());

        return redirect()->route('topics.show', $topic->id)->with('success', '更新成功！');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);
        $topic->delete();

        return redirect()->route('topics.index')->with('success', '成功删除！');
    }

    public function uploadImage(Request $request, ImageUploadHandler $uploader)
    {
        $data = [
            'success' => false,
            'msg' => '上传失败！',
            'file_path' => ''
        ];
        if ($file = $request->upload_file) {
            $result = $uploader->save($file, 'topics', Auth::id(), 1024);
            if ($result) {
                $data['file_path'] = Storage::disk('upload')->url($result['path']);
                $data['msg'] = '上传成功！';
                $data['success'] = true;
            }
        }
        return $data;
    }
}
