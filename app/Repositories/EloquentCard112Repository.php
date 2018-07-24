<?php

namespace App\Repositories;

use App\Models\Card112\Card112;
use App\Repositories\Contracts\Card112RepositoryInterface;
use Illuminate\Support\Facades\Validator;

class EloquentCard112Repository extends Repository implements Card112RepositoryInterface
{
    public function model()
    {
        return Card112::class;
    }

    public function createFilledWithRelations(array $data): Card112
    {
        $serviceReactions = $this->filterServiceReactions(array_get($data, 'service_reactions', []));
        $chronology = $this->filterChronology(array_get($data, 'chronology', []));

        /** @var $card112 Card112 */
        $card112 = $this->create($data);

        foreach ($serviceReactions as $serviceReaction){
            $card112->serviceReactions()->create($serviceReaction);
        }

        foreach ($chronology as $item){
            $card112->chronology()->create($item);
        }

        return $card112;
    }

    public function updateFilledWithRelations(array $data, int $id): Card112
    {
        $serviceReactions = $this->filterServiceReactions(array_get($data, 'service_reactions', []));
        $chronology = $this->filterChronology(array_get($data, 'chronology', []));

        $this->update($data, $id);

        /** @var $card112 Card112 */
        $card112 = $this->find($id);

        foreach ($serviceReactions as $serviceReaction){
            $serviceReactionModel = $card112->serviceReactions()->find(array_get($serviceReaction, 'id'));
            $serviceReactionModel->update($serviceReaction);
        }

        foreach ($chronology as $item){
            $itemModel = $card112->chronology()->find(array_get($item, 'id'));
            $itemModel->update($item);
        }

        return $card112;
    }

    private function filterServiceReactions(array $serviceReactions): array
    {
        return array_filter($serviceReactions, function ($serviceReaction) {
            $validator = Validator::make($serviceReaction, [
                'service_type_id' => 'required',
                'message_time' => 'required',
                'name' => 'required',
                'departure_time' => 'required',
                'arrival_time' => 'required'
            ]);
            return !$validator->fails();
        });
    }

    private function filterChronology(array $chronology) :array
    {
        return array_filter($chronology, function ($item) {
            $validator = Validator::make($item, [
                'time' => 'required',
                'comment' => 'required',
                'additional_comment' => 'required'
            ]);
            return !$validator->fails();
        });
    }
}