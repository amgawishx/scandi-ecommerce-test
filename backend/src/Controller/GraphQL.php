<?php

namespace MvpMarket\Controller;

use GraphQL\GraphQL as GraphQLBase;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;
use MvpMarket\Models\OrderModel;
use MvpMarket\Models\CategoryModel;
use MvpMarket\Models\ProductModel;
use MvpMarket\GraphQL\Types\TypesRegistry;
use RuntimeException;
use Throwable;

class GraphQL
{
    public static function handle()
    {
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods: POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(204);
            exit;
        }

        ini_set('display_errors', '1');
        error_reporting(E_ALL);

        try {
            $queryType = new ObjectType([
                'name' => 'Query',
                'fields' => [
                    'products' => [
                        'type' => Type::listOf(TypesRegistry::product()),
                        'resolve' => fn() => (new ProductModel())->getAll(),
                    ],
                    'product' => [
                        'type' => TypesRegistry::product(),
                        'args' => [
                            'id' => Type::nonNull(Type::string()),
                        ],
                        'resolve' => fn($root, array $args) => (new ProductModel())->getOne($args['id']),
                    ],
                    'categories' => [
                        'type' => Type::listOf(TypesRegistry::category()),
                        'resolve' => fn(): array => (new CategoryModel())->getAll(),
                    ],
                ],
            ]);

            $mutationType = new ObjectType([
                'name' => 'Mutation',
                'fields' => [
                    'placeOrder' => [
                        'type' => Type::boolean(),
                        'args' => [
                            'items' => Type::nonNull(Type::listOf(TypesRegistry::order())), // ✅ Using reusable Order InputObjectType
                        ],
                        'resolve' => fn($root, array $args) => (new OrderModel())->placeOrder($args['items']),
                    ],
                ],
            ]);

            $schema = new Schema(
                (new SchemaConfig())
                    ->setQuery($queryType)
                    ->setMutation($mutationType)
            );

            $rawInput = file_get_contents('php://input');
            if ($rawInput === false) {
                throw new RuntimeException('Failed to read input');
            }

            $input = json_decode($rawInput, true);
            $query = $input['query'] ?? null;
            $variableValues = $input['variables'] ?? null;

            $result = GraphQLBase::executeQuery($schema, $query, null, null, $variableValues);
            $output = $result->toArray();
        } catch (Throwable $e) {
            $output = [
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ];
        }

        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($output);
    }
}
