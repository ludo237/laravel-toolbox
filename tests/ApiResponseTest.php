<?php

namespace Ludo237\Toolbox\Tests;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;
use Ludo237\Toolbox\ApiResponse;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;

#[CoversClass(ApiResponse::class)]
class ApiResponseTest extends TestCase
{
    #[Test]
    public function it_can_be_prepared()
    {
        $response = ApiResponse::prepare('foo bar');

        $this->assertEquals('foo bar', $response->message);

        $response = new ApiResponse('foo bar');

        $this->assertEquals('foo bar', $response->message);
    }

    #[Test]
    public function message_can_be_set()
    {
        $response = (new ApiResponse())->message('foo bar');

        $this->assertEquals('foo bar', $response->message);
    }

    #[Test]
    public function details_can_be_set()
    {
        $response = (new ApiResponse())->details('foo bar');

        $this->assertEquals('foo bar', $response->details);
    }

    #[Test]
    public function http_status_code_can_be_set()
    {
        $response = (new ApiResponse())->status(500);

        $this->assertEquals(500, $response->statusCode);
    }

    #[Test]
    public function http_headers_can_be_set()
    {
        $response = (new ApiResponse())->addHeader('Content-Type', 'html');

        $this->assertIsArray($response->headers);
        $this->assertArrayHasKey('Content-Type', $response->headers);
        $this->assertEquals('html', $response->headers['Content-Type']);
    }

    #[Test]
    public function custom_error_items_can_be_set()
    {
        $response = (new ApiResponse())->addErrorItem('an error');
        $response->addErrorItem('error value', 'error key');
        $response->addErrorItem(['second key' => 'value']);

        $this->assertIsArray($response->errors);
        $this->assertEquals('an error', $response->errors[0]);
        $this->assertArrayHasKey('error key', $response->errors);
        $this->assertArrayHasKey('second key', $response->errors);
        $this->assertEquals('error value', $response->errors['error key']);
        $this->assertEquals('value', $response->errors['second key']);
    }

    #[Test]
    public function custom_data_items_can_be_set()
    {
        $response = (new ApiResponse())->addDataItem('a data');
        $response->addDataItem('data value', 'data key');
        $response->addDataItem(['second key' => 'value']);

        $this->assertIsArray($response->data);
        $this->assertEquals('a data', $response->data[0]);
        $this->assertArrayHasKey('data key', $response->data);
        $this->assertArrayHasKey('second key', $response->data);
        $this->assertEquals('data value', $response->data['data key']);
        $this->assertEquals('value', $response->data['second key']);
    }

    public function json_resource_can_be_set()
    {
        $users = UserFactory::new()->count(5)->make();

        $response = (new ApiResponse())->addDataResource(UserResource::collection($users));

        $this->assertIsArray($response->data);
        $this->assertCount(5, $response->data);

        $user = UserFactory::new()->make();

        $response->addDataResource(new UserResource($user));

        $this->assertIsArray($response->data);

        $response->addDataResource(new UserResource($user), 'user');

        $this->assertIsArray($response->data);
        $this->assertArrayHasKey('user', $response->data);
    }

    #[Test]
    public function oauth_access_token_can_be_set()
    {
        $tokenString = Str::random();
        $token = new NewAccessToken(new PersonalAccessToken(), $tokenString);

        $response = (new ApiResponse())->addNewAccessToken($token);

        $this->assertIsArray($response->data);
        $this->assertEquals('Bearer', $response->data['type']);
        $this->assertEquals('Sanctum', $response->data['provider']);
        $this->assertEquals($tokenString, $response->data['token']);
    }

    #[Test]
    public function it_sends_out_the_response()
    {
        $response = new ApiResponse();

        $this->assertInstanceOf(JsonResponse::class, $response->send());

        $build = $response->toArray();
        $this->assertIsArray($build);
        $this->assertArrayHasKey('status', $build);
        $this->assertArrayNotHasKey('message', $build);
        $this->assertArrayNotHasKey('details', $build);
        $this->assertArrayHasKey('data', $build);
    }
}
