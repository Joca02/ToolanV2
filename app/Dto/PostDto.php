<?php

namespace App\Dto;

class PostDto
{
    public $id_post;
    public $profile_picture;
    public $username;
    public $post_description;
    public $date;
    public $picture;
    public $isUserOwner;
    public $id_user;

    public function __construct($post, $currentUserId)
    {
        $this->id_post = $post->id_post;
        $this->profile_picture = $post->profile_picture;
        $this->username = $post->username;
        $this->post_description = $post->post_description;
        $this->date = $post->date;
        $this->picture = $post->picture;
        $this->isUserOwner = $post->id_user === $currentUserId;
        $this->id_user = $post->id_user;
    }
}
