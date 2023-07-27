<?php

namespace App\Enums;


enum UserType:string{
    case Staff = 'staff';
    case Student = 'student';

}


enum UserRoleEnum:int {
    case Admin = 1;
    case Finance = 2;
    case Teacher = 3;
    case NonTeacher = 4;
    case Inventory = 5;
}


enum UserStatusEnum:int {
    case Deactivated = 0;
    case Active = 1;
    case Suspended = 2;
    case Blocked = 3;
}





