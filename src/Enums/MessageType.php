<?php

namespace Laraditz\RealmChat\Enums;

enum MessageType: int
{
    use EnumTrait;

    case Text = 1;

    case Image = 2;
}
