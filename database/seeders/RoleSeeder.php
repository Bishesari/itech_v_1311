<?php

namespace Database\Seeders;

use App\Enums\RoleCode;
use App\Enums\RoleScope;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'code' => RoleCode::SuperAdmin->value,
                'name' => 'مدیر کل سیستم',
                'scope' => RoleScope::System->value,
                'description' => 'مدیر و ناظر کل سامانه با دسترسی مدیریتی در سطح سیستم.',
                'color' => 'red',
            ],
            [
                'code' => RoleCode::Founder->value,
                'name' => 'مؤسس',
                'scope' => RoleScope::Institute->value,
                'description' => 'مالک و مدیر کل آموزشگاه با نظارت بر تمام شعب و امور آموزشگاه.',
                'color' => 'teal',
            ],
            [
                'code' => RoleCode::BranchManager->value,
                'name' => 'مدیر شعبه',
                'scope' => RoleScope::Branch->value,
                'description' => 'مسئول اداره امور اجرایی، اداری و عملکرد یک شعبه.',
                'color' => 'blue',
            ],
            [
                'code' => RoleCode::EducationManager->value,
                'name' => 'مسئول آموزشی',
                'scope' => RoleScope::Branch->value,
                'description' => 'مسئول برنامه‌ریزی، نظارت و مدیریت امور آموزشی یک شعبه.',
                'color' => 'violet',
            ],
            [
                'code' => RoleCode::Administrative->value,
                'name' => 'کارمند اداری',
                'scope' => RoleScope::Branch->value,
                'description' => 'مسئول امور اجرایی، ثبت‌نام و فعالیت‌های اداری یک شعبه.',
                'color' => 'fuchsia',
            ],
            [
                'code' => RoleCode::Accountant->value,
                'name' => 'حسابدار',
                'scope' => RoleScope::Branch->value,
                'description' => 'مسئول امور مالی، شهریه‌ها، پرداخت‌ها و گزارش‌های مالی یک شعبه.',
                'color' => 'amber',
            ],
            [
                'code' => RoleCode::Instructor->value,
                'name' => 'مدرس',
                'scope' => RoleScope::Branch->value,
                'description' => 'مسئول آموزش و ارزیابی هنرجویان کلاس‌های خود.',
                'color' => 'cyan',
            ],
            [
                'code' => RoleCode::Student->value,
                'name' => 'هنرجو',
                'scope' => RoleScope::Branch->value,
                'description' => 'هنرجوی شرکت‌کننده در دوره‌های آموزشی.',
                'color' => 'lime',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['code' => $role['code']],
                [
                    'name' => $role['name'],
                    'scope' => $role['scope'],
                    'description' => $role['description'],
                    'color' => $role['color'],
                    'is_active' => true,
                ]
            );
        }
    }
}
