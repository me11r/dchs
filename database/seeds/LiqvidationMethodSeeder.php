<?php


class LiqvidationMethodSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        $names = [
            'первым стволом (стволами от емкости автоцистерны)',
            'с установкой пож. автомобилей на водоисточники',
            'от емкости нескольких автоцистерн (подвозом воды)',
            'подручными средствами',
            'до прибытия',
            'письменное заявление',
            'ранцевые системы пожаротушения',
            'совместно первым стволом и ранцевыми системами',
        ];

        (new App\Dictionary\LiquidationMethod)->truncate();
        foreach ($names as $name){
            (new \App\Dictionary\LiquidationMethod)
                ->fill(['name' => $name])
                ->save();
        }
    }
}