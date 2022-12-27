<?php
namespace App\Config;

enum BlogStyle: int
{
    case Default = 0;
    case TitleInsideLead = 1;
    case LeadOnTop = 2;
}