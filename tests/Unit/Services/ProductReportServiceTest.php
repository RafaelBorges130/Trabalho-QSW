<?php

namespace Tests\Unit\Services;

use App\Services\ProductReportService;
use PHPUnit\Framework\TestCase; // Importante: use o TestCase base do PHPUnit, não o do Laravel

class ProductReportServiceTest extends TestCase
{
    protected ProductReportService $service;

    // Método setup é executado antes de cada teste
    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ProductReportService();
    }

    // Teste 1
    public function test_it_calculates_correct_margin(): void
    {
        // Preço: 100, Custo: 50. Margem de 50%
        $margin = $this->service->calculateMargin(10000, 5000);
        $this->assertEquals(50.0, $margin);
    }

    // Teste 2
    public function test_it_handles_profit_loss_margin(): void
    {
        // Preço: 100, Custo: 120. Margem de -20% (Prejuízo)
        $margin = $this->service->calculateMargin(10000, 12000);
        $this->assertEquals(-20.0, $margin);
    }
    
    // Teste 3
    public function test_it_handles_zero_price_on_margin(): void
    {
        // Preço: 0, Custo: 50. Margem deve ser 0% para evitar divisão por zero
        $margin = $this->service->calculateMargin(0, 5000);
        $this->assertEquals(0.0, $margin);
    }

    // Teste 4
    public function test_it_formats_price_to_brl_correctly(): void
    {
        // 1050 centavos = R$ 10,50
        $formatted = $this->service->formatPriceToBRL(1050);
        $this->assertEquals('R$ 10,50', $formatted);
    }

    // Teste 5
    public function test_it_generates_a_simple_slug(): void
    {
        $slug = $this->service->generateSlug('Meu Novo Produto');
        $this->assertEquals('meu-novo-produto', $slug);
    }

    // Teste 6
    public function test_it_generates_a_slug_with_special_chars_and_trim(): void
    {
        $slug = $this->service->generateSlug('  Produto com Acentuação!  ');
        $this->assertEquals('produto-com-acentuacao', $slug);
    }
}