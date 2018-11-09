<?php

use Illuminate\Database\Seeder;
use App\Models\Notification\NotificationStatus;
use App\Enums\NotificationStatusType;

class NotificationStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        \Schema::disableForeignKeyConstraints();
        (new NotificationStatus())->truncate();

        (new NotificationStatus())->insert([
            [
                'id' => NotificationStatusType::CREATED,
                'name' => 'Создано'
            ],
            [
                'id' => NotificationStatusType::SENT,
                'name' => 'Отправлено'
            ],
            [
                'id' => NotificationStatusType::DELIVERED,
                'name' => 'Доставлено'
            ],
            [
                'id' => NotificationStatusType::ERROR,
                'name' => 'Ошибка'
            ],
            [
                'id' => NotificationStatusType::TOKEN_NOT_FOUND,
                'name' => 'Устройство не привязано'
            ],
        ]);

        \Schema::enableForeignKeyConstraints();
    }
}
