<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    protected $info;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->info = DB::table('user_infos')->where('user_id', $resource->id)->first();
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $name =  $this->info?->nom . ($this->info?->prenom ? ' ' .  $this->info?->prenom : '');

        $nameForAvatar = $name ? trim(collect(explode(' ', $name))->map(function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' ')) : $this->email;

        return [
            ...parent::toArray($request),
            'name' => $name,
            'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($nameForAvatar) . '&color=7F9CF5&background=EBF4FF',
            'info' => $this->info,
            // 'tokens' => $this->tokens,
            // 'roles' => $this->getRoleNames(),
        ];
    }
}
