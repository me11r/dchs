#!/usr/bin/env bash
cd ..
php artisan scout:import "App\Models\SpecialPlan"
php artisan scout:import "App\OperationalCard"
