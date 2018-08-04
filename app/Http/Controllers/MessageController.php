<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageResource;
use App\Models\Nickname;
use App\Repositories\Contracts\ChatInterface;
use App\Repositories\Contracts\MessageInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class MessageController extends Controller
{
    private $repository;
    private $chatRepository;
    private $chat;

    public function __construct(ChatInterface $chat, MessageInterface $message)
    {
        parent::__construct();
        $this->repository = $message;
        $this->chatRepository = $chat;
        if (!\request('chatId')) abort(404);
        $this->chat = $this->chatRepository->find(\request('chatId'));
        if (!$this->chat) abort(404);
    }

    public function index()
    {
        $items = $this->repository->where('chat_id', '=', $this->chat->id)->with(['nickname'])->get();

        return View::make('message.index')
            ->with('chat', $this->chat)
            ->with('items', $items)
            ->render();
    }

    public function create()
    {
        return View::make('message.add')
            ->with('nicknames', collect(Nickname::orderBy('name')->get(['id', 'name']))->toArray())
            ->with('chat', $this->chat)
            ->render();
    }

    public function store(Request $request)
    {
        $this->repository->create($request->all());
        return redirect(route('messages.index', ['chatId' => $this->chat->id]));
    }

    public function show($id)
    {
        abort(418, 'Раздел в разработке');
    }

    public function edit($id)
    {
        return View::make('message.edit')
            ->with('model', new MessageResource($this->repository->find($id)))
            ->with('nicknames', collect(Nickname::orderBy('name')->get(['id', 'name']))->toArray())
            ->with('chat', $this->chat)
            ->render();
    }

    public function update(Request $request, $id)
    {
        $this->repository->update($request->all(), $id);
        return redirect(route('messages.index', ['chatId' => $this->chat->id]));
    }

    public function destroy($id)
    {
        $this->repository->delete($id);
        return redirect(route('messages.index', ['chatId' => $this->chat->id]));
    }
}
