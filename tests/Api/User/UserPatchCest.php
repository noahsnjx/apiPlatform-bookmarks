<?php

namespace App\Tests\Api\User;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Tests\Support\ApiTester;
use Codeception\Util\HttpCode;

class UserPatchCest
{
    protected static function expectedProperties(): array
    {
        return [
            'id' => 'integer',
            'login' => 'string',
            'firstname' => 'string',
            'lastname' => 'string',
            'mail' => 'string:email',
        ];
    }

    public function anonymousUserForbiddenToPatchUser(ApiTester $I): void
    {
        // 1. 'Arrange'
        UserFactory::createOne();

        // 2. 'Act'
        $I->sendPatch('/api/users/1');

        // 3. 'Assert'
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
    }

    public function authenticatedUserForbiddenToPatchOtherUser(ApiTester $I): void
    {
        // 1. 'Arrange'
        /** @var $user User */
        $user = UserFactory::createOne()->object();
        UserFactory::createOne();
        $I->amLoggedInAs($user);

        // 2. 'Act'
        $I->sendPatch('/api/users/2');

        // 3. 'Assert'
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function authenticatedUserCanPatchOwnData(ApiTester $I): void
    {
        // 1. 'Arrange'
        $dataInit = [
            'lastname' => 'lastname1',
            'firstname' => 'firstname1',
            'login' => 'login1',
        ];

        /** @var $user User */
        $user = UserFactory::createOne($dataInit)->object();
        $I->amLoggedInAs($user);

        // 2. 'Act'
        $dataPatch = [
            'lastname' => 'lastname2',
            'firstname' => 'firstname2',
            'login' => 'login2',
        ];
        $I->sendPatch('/api/users/1', $dataPatch);

        // 3. 'Assert'
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(User::class, '/api/users/1');
        $I->seeResponseIsAnItem(self::expectedProperties(), $dataPatch);
    }

    public function authenticatedUserCanChangeHisPassword(ApiTester $I): void
    {
        // 1. 'Arrange'
        $dataInit = [
            'login' => 'user1',
            'password' => 'password',
        ];
        /** @var $user User */
        $user = UserFactory::createOne($dataInit)->object();
        $I->amLoggedInAs($user);

        // 2. 'Act'
        $dataPatch = ['password' => 'new password'];
        $I->sendPatch('/api/users/1', $dataPatch);

        // 3. 'Assert'
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
        $I->seeResponseIsAnEntity(User::class, '/api/users/1');
        $I->seeResponseIsAnItem(self::expectedProperties());

        $I->amOnPage('/logout');
        // Don't check response code since homepage is not configured (404)
        // $I->seeResponseCodeIsSuccessful();
        $I->amOnPage('/login');
        $I->seeResponseCodeIsSuccessful();
        $I->submitForm(
            'form',
            ['login' => 'user1', 'password' => 'new password'],
            'Authentication'
        );
        $I->seeResponseCodeIsSuccessful();
        $I->seeInCurrentUrl('/api/docs');
    }
}
