<?php

namespace Jhavenz\NovaExtendedFields\Enums;

enum AuthTypes: string
{
    case OAUTH1 = 'oAuth1';
    case OAUTH2 = 'oAuth2';
    case BASIC = 'basic';
    case JWT = 'jwt';
    case TOKEN = 'token';
    case SESSION = 'session';
    case SERVICE_ACCOUNT = 'service_account';

    public static function uiOptions(): array
    {
        return array_map(fn(AuthTypes $option) => str($option->value)->ucfirst()->toString(), self::cases());
    }
}
