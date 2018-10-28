<?php

namespace App\Repositories;

use App\Models\Card112\Card112;
use App\Models\Card112\Card112Chronology;
use App\Models\Card112\Card112ServiceReaction;
use App\Models\ServiceType;
use App\Repositories\Contracts\Card112RepositoryInterface;
use App\Ticket101ServicePlan;
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
        $services = ServiceType::all()->pluck('id')->toArray();

        /** @var $card112 Card112 */
        $card112 = $this->create($data);

        $card112->serviceReactions()->saveMany(array_map(function ($item){
            return new Card112ServiceReaction($item);
        }, $serviceReactions));

        /*службы взаимодействия - создание шаблонов путевых листов*/
        foreach ($services as $service) {
            Ticket101ServicePlan::create([
                'card112_id' => $card112->id
            ]);
        }

        $card112->chronology()->saveMany(array_map(function ($item){
            return new Card112Chronology($item);
        }, $chronology));

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
            $fill = [
                'card112_id' => $card112->id,
                'service_type_id' => $serviceReaction['service_type_id'],
                'message_time' => $serviceReaction['message_time'],
                'name' => $serviceReaction['name'],
                'departure_time' => $serviceReaction['departure_time'],
                'arrival_time' => $serviceReaction['arrival_time']
            ];
            Card112ServiceReaction::updateOrCreate([
                'service_type_id' => $serviceReaction['service_type_id'],
                'card112_id' => $card112->id,
            ], $fill);
        }

        $card112->chronology()->delete();
        $card112->chronology()->saveMany(array_map(function ($item){
            return new Card112Chronology($item);
        }, $chronology));

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
                'comment' => 'required'
            ]);
            return !$validator->fails();
        });
    }
}