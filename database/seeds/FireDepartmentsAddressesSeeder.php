<?php


use Illuminate\Database\Seeder;

class FireDepartmentsAddressesSeeder extends Seeder
{
    private $cityAreas;
    private $departments;
    public function run()
    {
        $addresses = [
            'ПЧ-1, ул.Сарбайская 129, Медеуский',
            'ПЧ-2, пр.Раймбека 172, Жетысуский',
            'ПЧ-3, ул.Макатаева 129 А, Алмалинский',
            'ПЧ-4, ул.Дунентаева 6 А, Турксибский',
            'ПЧ-5, ул.Карпатская 16, Алатауский',
            'ПЧ-6, пр.Гагарина 153/8, Бостандыкский',
            'СПЧ-7, ул.Сайна 20 А, Ауэзовский',
            'СПЧ-8, ул.Кунаева 132, Медеуский',
            'СПЧ-9, ул.Горная 568/2, Медеуский',
            'ПЧ-10, ул.Ибрагимова 21, Медеуский',
            'СПЧ-11, пр.Суюнабая 247, Турксибский',
            'ПЧ-12, мкр.Дорожник 27 А, Жетысуский',
            'СО, пр.Рыскулова 91, Жетысуйский',
            'СПЧ-14, мрк.Карасу,ул.Шаяхметова 1, Алатауский',
            'СПЧ-15, мрк.Акбулак, ул.Чуланова 105, Алатауский',
            'ПП-16, ул.Рыскулбекова 43 А, Бостандыкский',
            'ПП-17, п.Акжар, ул.Даулеткерея 1, Наурызбайский',

        ];
        $this->cityAreas = \App\Dictionary\CityArea::select(['id', 'name'])->get()->toArray();
        $this->departments = \App\FireDepartment::all();
        foreach ($addresses as $address) {
            $parts = explode(', ', $address);
            $department =$this->getDepartment($parts[0]);
            $area = $this->getArea($parts[2]);
            if($department) {
                $department->address = $parts[1];
                $department->city_area_id = $area ? $area : 6; // Опечатка в названии Медеуского района и в базе он записан Медейским
                $department->save();
            }
        }

    }

    private function getArea($area)
    {
        return array_search($area, array_column($this->cityAreas, 'name'));
    }

    private function getDepartment($department)
    {
        foreach ($this->departments as $fireDepartment) {
            if($fireDepartment->title === trim($department)){
                return $fireDepartment;
            }
        }
        return null;
    }
}
