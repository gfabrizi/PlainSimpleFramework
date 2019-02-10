<?php
namespace gfabrizi\PlainSimpleFramework\Mappers;

interface RelationInterface
{
    public function getTargetClass();

    public function getLocalColumn();

    public function getForeignColumn();
}