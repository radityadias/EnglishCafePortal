<?php

namespace App\Enums;

enum Division: string
{
    case GeneralAffairs = 'general_affairs';
    case OperationalChef = 'operational_chef';
    case MasterChef = 'master_chef';
    case AdminSales = 'admin_sales';
    case MarketingSales = 'marketing_sales';
    case CustomerCare = 'customer_care';
    case HumanResourcesDevelopment = 'human_resources_development';
    case GraphicDesign = 'graphic_design';

    public function getLabel(): string
    {
        return match ($this) {
            self::GeneralAffairs => 'General Affairs',
            self::OperationalChef => 'Operational Chef',
            self::MasterChef => 'Master Chef',
            self::AdminSales => 'Admin Sales',
            self::MarketingSales => 'Marketing & Sales',
            self::CustomerCare => 'Customer Care',
            self::HumanResourcesDevelopment => 'Human Resources & Development',
            self::GraphicDesign => 'Graphic Design',
        };
    }

    public static function kpiTracked() : array
    {
        return [self::MasterChef, self::OperationalChef];
    }
}
