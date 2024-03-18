<?php

namespace Ludo237\Toolbox;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Laravel\Sanctum\NewAccessToken;
use stdClass;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ApiResponse
{
    public array $headers = [];

    public ?string $message = null;

    public ?string $details = null;

    public int $statusCode = ResponseAlias::HTTP_OK;

    public array $data = [];

    public ?stdClass $meta = null;

    public ?stdClass $links = null;

    public array $errors = [];

    public function __construct(?string $message = null)
    {
        if (! empty($message)) {
            $this->message($message);
        }
    }

    public static function prepare(?string $message = null): self
    {
        return new self($message);
    }

    public function message(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function details(string $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function status(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function addHeader(string $key, mixed $value): self
    {
        $header = [$key => $value];

        $this->headers = array_merge($this->headers, $header);

        return $this;
    }

    private function parseCustomItem(string|int|array $item, string $key): array
    {
        // By default we assume that the $item is a simple string or integer
        $customItem = [$item];

        // If instead it's an array we change the current $dataItem to respect the array
        if (is_array($item)) {
            $customItem = $item;
        }

        // If the key is not empty it means that we need to build an associative array
        if (! empty($key)) {
            return [$key => $item];
        }

        return $customItem;
    }

    public function addErrorItem(string|int|array $item, string $key = ''): self
    {
        $this->errors = array_merge(
            $this->errors,
            $this->parseCustomItem($item, $key)
        );

        return $this;
    }

    public function addDataItem(mixed $item, string $key = ''): self
    {
        $this->data = array_merge(
            $this->data,
            $this->parseCustomItem($item, $key)
        );

        return $this;
    }

    public function addDataResource(JsonResource $resource, string $key = ''): self
    {
        // If the resource passed is a collection we need to extract and normalize it
        if ($resource instanceof ResourceCollection) {
            $responseData = $resource->response()->getData();

            $this->meta = $responseData->meta ?? null;
            $this->links = $responseData->links ?? null;
        }

        $this->addDataItem($resource->jsonSerialize(), $key);

        return $this;
    }

    public function addNewAccessToken(NewAccessToken $newAccessToken, string $key = ''): self
    {
        $this->addDataItem([
            'type' => 'Bearer',
            'provider' => 'Sanctum',
            'token' => $newAccessToken->plainTextToken,
        ], $key);

        return $this;
    }

    private function buildResponseContent(): array
    {
        $responseContent = [
            'status' => $this->statusCode,
            'data' => $this->data,
        ];

        if (! empty($this->message)) {
            $responseContent = array_merge($responseContent, [
                'message' => $this->message,
            ]);
        }

        if (! empty($this->details)) {
            $responseContent = array_merge($responseContent, [
                'details' => $this->details,
            ]);
        }

        if (! empty($this->errors)) {
            unset($responseContent['data']);
            $responseContent = array_merge($responseContent, [
                'errors' => $this->errors,
            ]);
        }

        if (! is_null($this->meta)) {
            $responseContent = array_merge($responseContent, [
                'meta' => $this->meta,
            ]);
        }

        if (! is_null($this->links)) {
            return array_merge($responseContent, [
                'links' => $this->links,
            ]);
        }

        return $responseContent;
    }

    public function send(): JsonResponse
    {
        $content = $this->buildResponseContent();

        return new JsonResponse($content, $this->statusCode, $this->headers);
    }

    public function toArray(): array
    {
        return $this->buildResponseContent();
    }
}
