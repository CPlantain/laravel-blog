<?php

namespace App;

trait UserStatus 
{
	public function ban()
    {
        $this->status = User::IS_BANNED;
        $this->save();
    }

    protected function unban()
    {
        $this->status = User::IS_ACTIVE;
        $this->save();
    }

    public function toggleStatus()
    {
        if ($this->status == User::IS_BANNED) {
            return $this->unban();
        }

        return $this->ban();
    }

    public function getStatus()
    {
        return $this->status == User::IS_BANNED ? 'Banned' : 'Active';
    }

    public function isBanned()
    {
        return $this->status == User::IS_BANNED ? true : false;
    }
}