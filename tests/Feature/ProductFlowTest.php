<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product; // Importe o Model

class ProductFlowTest extends TestCase
{
    // Este trait mágico reseta o banco de dados após cada teste
    use RefreshDatabase;

    // Teste 1
    public function test_it_can_create_a_product_successfully(): void
    {
        $productData = [
            'name' => 'Produto Teste',
            'price_in_cents' => 1000, // R$ 10,00
            'cost_in_cents' => 500,  // R$ 5,00
        ];

        // Simula uma requisição POST para a rota 'products.store'
        $response = $this->post(route('products.store'), $productData);

        // Verifica se fomos redirecionados para a listagem (products.index)
        $response->assertRedirect(route('products.index'));
        // Verifica se a mensagem de sucesso está na sessão
        $response->assertSessionHas('success', 'Produto criado com sucesso!');

        // Verifica se os dados existem no banco de dados
        $this->assertDatabaseHas('products', [
            'name' => 'Produto Teste',
            'price_in_cents' => 1000
        ]);
    }

    // Teste 2
    public function test_it_fails_validation_if_name_is_missing(): void
    {
        $productData = [
            'name' => '', // Nome vazio
            'price_in_cents' => 1000,
            'cost_in_cents' => 500,
        ];

        $response = $this->post(route('products.store'), $productData);

        // Verifica se a sessão contém um erro de validação para o campo 'name'
        $response->assertSessionHasErrors('name');
        
        // Verifica se NADA foi inserido no banco
        $this->assertDatabaseCount('products', 0);
    }

    // Teste 3
    public function test_it_fails_validation_if_price_is_not_positive(): void
    {
        $productData = [
            'name' => 'Produto com Preço Zero',
            'price_in_cents' => 0, // Preço 0 (inválido pela regra 'min:1')
            'cost_in_cents' => 500,
        ];

        $response = $this->post(route('products.store'), $productData);

        // Verifica o erro de validação no campo 'price_in_cents'
        $response->assertSessionHasErrors('price_in_cents');
    }

    // Teste 4
    public function test_it_can_list_products_on_index_page(): void
    {
        // Crie 3 produtos no banco de dados (usando Factory, se tiver, ou o Model)
        Product::create(['name' => 'Produto A', 'price_in_cents' => 100, 'cost_in_cents' => 50]);
        Product::create(['name' => 'Produto B', 'price_in_cents' => 200, 'cost_in_cents' => 100]);

        // Simula uma requisição GET para a rota 'products.index'
        $response = $this->get(route('products.index'));

        // Verifica se a resposta foi OK (Status 200)
        $response->assertStatus(200);

        // Verifica se os nomes dos produtos aparecem na página
        $response->assertSee('Produto A');
        $response->assertSee('Produto B');
    }
}