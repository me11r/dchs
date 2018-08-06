<?php

namespace App\Http\Controllers;

use App\Http\Resources\NicknameResource;
use App\Repositories\Contracts\NicknameInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class NicknameController extends Controller
{
    private $repository;

    public function __construct(NicknameInterface $nickname)
    {
        parent::__construct();
        $this->repository = $nickname;
    }

    public function index()
    {
        $items = $this->repository->all();

        return View::make('nickname.index')
            ->with('items', $items)
            ->render();
    }

    public function create()
    {
        return View::make('nickname.add')
            ->render();
    }

    public function store(Request $request)
    {
        $this->repository->create($request->all());
        return redirect(route('nicknames.index'));
    }

    public function show($id)
    {
        abort(418, 'Раздел в разработке');
    }

    public function edit($id)
    {
        return View::make('nickname.edit')
            ->with('model', new NicknameResource($this->repository->find($id)))
            ->render();
    }

    public function update(Request $request, $id)
    {
        $this->repository->update($request->all(), $id);
        return redirect(route('nicknames.index'));
    }

    public function destroy($id)
    {
        $this->repository->delete($id);
        return redirect(route('nicknames.index'));
    }
}
