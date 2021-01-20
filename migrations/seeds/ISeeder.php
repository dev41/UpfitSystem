<?php
namespace app\migrations\seeds;

interface ISeeder
{
    public function seed();
    public function clean();
}