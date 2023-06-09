<?php

namespace App\Tests\Api\User;

use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;

class UserGetAvatarCest
{
    public function getAvatar(ApiTester $I): void
    {
        // 1. 'Arrange'
        $user = UserFactory::createOne();

        // 2. 'Act'
        $I->sendGet('/api/users/1/avatar');

        /** @var resource $avatar */
        $avatar = $user->getAvatar();

        // 3. 'Assert'
        $I->seeResponseCodeIsSuccessful();
        $I->seeHttpHeader('Content-Type', 'image/png');
        $I->seeResponseContains(stream_get_contents($avatar, -1, 0));
    }
}
