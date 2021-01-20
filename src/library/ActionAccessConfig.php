<?php

namespace app\src\library;

class ActionAccessConfig
{
    private $authExpect = [];
    private $controlExpect = [];

    public function addAuthExpect(array $actions)
    {
        $this->authExpect = array_merge($this->authExpect, $actions);
        return $this;
    }

    public function setAuthExpect(array $actions)
    {
        $this->authExpect = $actions;
        return $this;
    }

    public function getAuthExpect(): array
    {
        return $this->authExpect;
    }

    public function addControlExpect(array $actions)
    {
        $this->controlExpect = array_merge($this->controlExpect, $actions);
        return $this;
    }

    public function setControlExpect(array $actions)
    {
        $this->controlExpect = $actions;
        return $this;
    }

    public function getControlExpect(): array
    {
        return $this->controlExpect;
    }
}