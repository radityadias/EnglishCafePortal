<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;

class Help extends Page
{
    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Panduan';

    protected static ?string $title = 'Panduan Penggunaan';

    protected string $view = 'filament.pages.help';

    public function getSubheading(): ?string
    {
        return 'Panduan singkat penggunaan Sistem Kehadiran Digital';
    }
}