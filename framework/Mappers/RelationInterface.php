<?php
namespace gfabrizi\PlainSimpleFramework\Mappers;

interface RelationInterface
{
    public function getTargetClass(): string;

    public function getLocalColumn(): string;

    public function getForeignColumn(): string;
}