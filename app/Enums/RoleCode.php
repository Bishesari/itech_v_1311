<?php

namespace App\Enums;

enum RoleCode: string
{
    case SuperAdmin = 'super_admin';
    case Founder = 'founder';
    case BranchManager = 'branch_manager';
    case EducationManager = 'education_manager';
    case Administrative = 'administrative';
    case Accountant = 'accountant';
    case Instructor = 'instructor';
    case Student = 'student';
}
