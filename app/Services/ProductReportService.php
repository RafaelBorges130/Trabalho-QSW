<?php

namespace App\Services;

use Illuminate\Support\Str;

class ProductReportService
{
    /**
     * Calcula a margem de lucro em porcentagem.
     */
    public function calculateMargin(int $priceInCents, int $costInCents): float
    {
        if ($priceInCents <= 0) {
            return 0.0;
        }
        
        $profit = $priceInCents - $costInCents;
        $margin = ($profit / $priceInCents) * 100;
        
        return round($margin, 2);
    }

    /**
     * Formata o preço para BRL (ex: R$ 10,50).
     */
    public function formatPriceToBRL(int $priceInCents): string
    {
        $priceInReais = $priceInCents / 100;
        return 'R$ ' . number_format($priceInReais, 2, ',', '.');
    }

    /**
     * Gera um "slug" (URL amigável) a partir do nome.
     */
    public function generateSlug(string $name): string
    {
        return Str::slug(trim($name));
    }
}