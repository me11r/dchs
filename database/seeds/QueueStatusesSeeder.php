<?php

use Illuminate\Database\Seeder;
use App\Enums\QueueStatusType;

class QueueStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'name' => 'Создано',
                'slug' => QueueStatusType::CREATED
            ],
            [
                'name' => 'Добавлено в очередь',
                'slug' => QueueStatusType::QUEUED
            ],
            [
                'name' => 'В процессе',
                'slug' => QueueStatusType::IN_PROGRESS
            ],
            [
                'name' => 'Завершено',
                'slug' => QueueStatusType::ENDED
            ],
            [
                'name' => 'Ошибка',
                'slug' => QueueStatusType::ERROR
            ]
        ];

        foreach ($items as $item) {
            (new \App\Models\QueueStatus)->firstOrCreate(
                ['slug' => $item['slug']],
                $item
            );
        }
    }
}
