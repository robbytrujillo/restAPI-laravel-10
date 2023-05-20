<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    // define properti (20052023)
    public $status; //menyimpan status yang ingin ditampilkan dalam response API

    public $message; // menyimpan pesan yang ingin ditampilkan dalam response API

    public $resource; // menyimpan data yang akan dikirmkan dalam response API

    // buat function __construct JSON
    public function __construct($status, $message, $resource) {
        parent::__construct($resource);
        $this->status = $status;
        $this->message = $message;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

     // membuat function toArray (20052023)
    public function toArray($request)
    {
        return [
            'success' => $this->status,
            'message' => $this->message,
            'data' => $this->resource
        ];
    }
}
