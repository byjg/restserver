<?php

namespace Tests;

use ByJG\RestServer\Exception\OperationIdInvalidException;
use ByJG\RestServer\Exception\SchemaInvalidException;
use ByJG\RestServer\Exception\SchemaNotFoundException;
use ByJG\RestServer\HandleOutput\JsonCleanHandler;
use ByJG\RestServer\HandleOutput\XmlHandler;
use ByJG\RestServer\RoutePattern;
use ByJG\RestServer\ServerRequestHandler;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\InvalidArgumentException;

class ServerHandlerTest extends TestCase
{
    /**
     * @var ServerRequestHandler
     */
    protected $object;

    public function setUp()
    {
        $this->object = new ServerRequestHandler();
    }

    public function tearDown()
    {
        $this->object = null;
    }

    /**
     * @throws OperationIdInvalidException
     * @throws SchemaInvalidException
     * @throws SchemaNotFoundException
     * @throws InvalidArgumentException
     */
    public function testGenerateRoutesSwagger()
    {
        $this->object->setPathHandler('get', '/v2/pet/{petId}', JsonCleanHandler::class);
        $this->object->setRoutesSwagger(__DIR__ . '/swagger-example.json');

        $this->assert();
    }

    /**
     * @throws OperationIdInvalidException
     * @throws SchemaInvalidException
     * @throws SchemaNotFoundException
     * @throws InvalidArgumentException
     */
    public function testGenerateRoutesOpenApi()
    {
        $this->object->setPathHandler('get', '/v2/pet/{petId}', JsonCleanHandler::class);
        $this->object->setDefaultHandler(new XmlHandler());
        $this->object->setRoutesSwagger(__DIR__ . '/openapi-example.json');

        $this->assert();
    }

    protected function assert()
    {
        $this->assertEquals(
            [
                new RoutePattern(
                    "GET",
                    "/v2/pet/findByStatus",
                    "ByJG\RestServer\HandleOutput\XmlHandler",
                    "findPetsByStatus",
                    "PetStore\Pet"
                ),
                new RoutePattern(
                    "GET",
                    "/v2/pet/findByTags",
                    "ByJG\RestServer\HandleOutput\XmlHandler",
                    "findPetsByTags",
                    "PetStore\Pet"
                ),
                new RoutePattern(
                    "POST",
                    "/v2/pet",
                    "ByJG\RestServer\HandleOutput\XmlHandler",
                    "addPet",
                    "PetStore\Pet"
                ),
                new RoutePattern(
                    "PUT",
                    "/v2/pet",
                    "ByJG\RestServer\HandleOutput\JsonHandler",
                    "updatePet",
                    "PetStore\Pet"
                ),
                new RoutePattern(
                    "GET",
                    "/v2/store/inventory",
                    "ByJG\RestServer\HandleOutput\JsonHandler",
                    "getInventory",
                    "PetStore\Pet"
                ),
                new RoutePattern(
                    "POST",
                    "/v2/store/order",
                    "ByJG\RestServer\HandleOutput\XmlHandler",
                    "placeOrder",
                    "PetStore\Pet"
                ),
                new RoutePattern(
                    "POST",
                    "/v2/user/createWithArray",
                    "ByJG\RestServer\HandleOutput\XmlHandler",
                    "createUsersWithArrayInput",
                    "PetStore\Pet"
                ),
                new RoutePattern(
                    "POST",
                    "/v2/user/createWithList",
                    "ByJG\RestServer\HandleOutput\XmlHandler",
                    "createUsersWithListInput",
                    "PetStore\Pet"
                ),
                new RoutePattern(
                    "GET",
                    "/v2/user/login",
                    "ByJG\RestServer\HandleOutput\XmlHandler",
                    "loginUser",
                    "PetStore\Pet"
                ),
                new RoutePattern(
                    "GET",
                    "/v2/user/logout",
                    "ByJG\RestServer\HandleOutput\XmlHandler",
                    "logoutUser",
                    "PetStore\Pet"
                ),
                new RoutePattern(
                    "POST",
                    "/v2/user",
                    "ByJG\RestServer\HandleOutput\XmlHandler",
                    "createUser",
                    "PetStore\Pet"
                ),
                new RoutePattern(
                    "POST",
                    "/v2/pet/{petId}/uploadImage",
                    "ByJG\RestServer\HandleOutput\JsonHandler",
                    "uploadFile",
                    "PetStore\Pet"
                ),
                new RoutePattern(
                    "GET",
                    "/v2/pet/{petId}",
                    "ByJG\RestServer\HandleOutput\JsonCleanHandler",
                    "getPetById",
                    "PetStore\Pet"
                ),
                new RoutePattern(
                    "POST",
                    "/v2/pet/{petId}",
                    "ByJG\RestServer\HandleOutput\XmlHandler",
                    "updatePetWithForm",
                    "PetStore\Pet"
                ),
                new RoutePattern(
                    "DELETE",
                    "/v2/pet/{petId}",
                    "ByJG\RestServer\HandleOutput\XmlHandler",
                    "deletePet",
                    "PetStore\Pet"
                ),
                new RoutePattern(
                    "GET",
                    "/v2/store/order/{orderId}",
                    "ByJG\RestServer\HandleOutput\XmlHandler",
                    "getOrderById",
                    "PetStore\Pet"
                ),
                new RoutePattern(
                    "DELETE",
                    "/v2/store/order/{orderId}",
                    "ByJG\RestServer\HandleOutput\XmlHandler",
                    "deleteOrder",
                    "PetStore\Pet"
                ),
                new RoutePattern(
                    "GET",
                    "/v2/user/{username}",
                    "ByJG\RestServer\HandleOutput\XmlHandler",
                    "getUserByName",
                    "PetStore\Pet"
                ),
                new RoutePattern(
                    "PUT",
                    "/v2/user/{username}",
                    "ByJG\RestServer\HandleOutput\XmlHandler",
                    "updateUser",
                    "PetStore\Pet"
                ),
                new RoutePattern(
                    "DELETE",
                    "/v2/user/{username}",
                    "ByJG\RestServer\HandleOutput\XmlHandler",
                    "deleteUser",
                    "PetStore\Pet"
                ),
            ],
            $this->object->getRoutes()
        );
    }
}
