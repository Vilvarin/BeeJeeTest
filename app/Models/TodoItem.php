<?php

namespace App\Models;

final class TodoItem extends Model
{
    public int $id;
    public string $name;
    public string $email;
    public string $content;
    public string $status;

    private string $created_at;
    private string $updated_at;

    public function __construct(array $row)
    {
        foreach ($row as $key => $value) {
            $this->$key = $value;
        }
    }

    public function isEdited()
    {
        return strtotime($this->updated_at) > strtotime($this->created_at);
    }
}
