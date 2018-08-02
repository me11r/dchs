<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChatResource;
use App\Repositories\Contracts\ChatInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ChatController extends Controller
{
    private $repository;

    public function __construct(ChatInterface $chat)
    {
        parent::__construct();
        $this->repository = $chat;
    }

    public function index()
    {
        $items = $this->repository->orderBy('id', 'DESC')->get();

        return View::make('chat.index')
            ->with('items', $items)
            ->render();
    }

    public function create()
    {
        return View::make('chat.add')
            ->render();
    }

    public function store(Request $request)
    {
        $this->repository->create($request->all());
        return redirect(route('chats.index'));
    }

    public function show($id)
    {
        abort(418, 'Раздел в разработке');
    }

    public function edit($id)
    {
        return View::make('chat.edit')
            ->with('model', new ChatResource($this->repository->find($id)))
            ->render();
    }

    public function update(Request $request, $id)
    {
        $this->repository->update($request->all(), $id);
        return redirect(route('chats.index'));
    }

    public function destroy($id)
    {
        $this->repository->delete($id);
        return redirect(route('chats.index'));
    }
}
