<?php

declare(strict_types=1);

namespace Webmaster;

enum Environment: string
{
    case PRODUCTION = 'production';
    case DEVELOPMENT = 'development';
    case TESTING = 'testing';
}
