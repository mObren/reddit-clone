<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
            return [
            'id' => $this->id,
            'userId' => $this->user_id,
            'parentId' => $this->parent_id,
            'commentableId' => $this->commentable_id,
            'commentableType' => $this->commentable_type,
            'body' => $this->body,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
    ];
    }
}
