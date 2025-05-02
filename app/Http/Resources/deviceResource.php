<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class deviceResource extends JsonResource
{
    public $status;
    public $message;
    public $resource;

    public function __construct($resource, $message, $status)
    {
        $this->status = $status;
        $this->message = $message;
        parent::__construct($resource);
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
                'status' => $this->status,
                'message' => $this->message,
                'data' => $this->resource,
            ];
    }
}
