<?php


namespace App\Tests\AppBundle\Controller;


use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTest extends WebTestCase
{


    public function testCreateUsers()
    {
        $faker = Factory::create();

        $userData = [];
        $userData['firstName'] = $faker->firstName();
        $userData['lastName'] = $faker->lastName();
        $userData['phoneNumber'] = $faker->phoneNumber();
        $userData['email'] = $faker->email();
        $userData['address'] = $faker->address();


        $client = static::createClient();

        $client->request('POST', '/api/users', $userData);
        $this->assertResponseIsSuccessful();

        $createdUser = json_decode($client->getResponse()->getContent(), true);
        $userData['id'] = $createdUser['id'];

        $this->assertEquals($userData, $createdUser);
    }

    public function testListUsers()
    {
        $client = static::createClient();

        $client->request('GET', '/api/users');
        $this->assertResponseIsSuccessful();
        $allUsers = json_decode($client->getResponse()->getContent(), true);

        $this->assertGreaterThanOrEqual(1,count($allUsers));
    }

    public function testGetUser()
    {
        $client = static::createClient();

        $client->request('GET', '/api/users');
        $this->assertResponseIsSuccessful();
        $allUsers = json_decode($client->getResponse()->getContent(), true);

        $randomUser = $allUsers[array_rand($allUsers)];

        $client->request('GET', '/api/users/'.$randomUser['id']);
        $this->assertResponseIsSuccessful();
        $userFetched = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($randomUser,$userFetched);
    }

    public function testPatchUser()
    {
        $client = static::createClient();
        $faker = Factory::create();

        $client->request('GET', '/api/users');
        $this->assertResponseIsSuccessful();
        $allUsers = json_decode($client->getResponse()->getContent(), true);

        $randomUser = $allUsers[array_rand($allUsers)];

        $phoneNumber = $faker->phoneNumber();

        $client->request('PATCH', '/api/users/'.$randomUser['id'],['phoneNumber' => $phoneNumber]);
        $this->assertResponseIsSuccessful();
        $userFetched = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($phoneNumber,$userFetched['phoneNumber']);
    }

    public function testDeleteUser()
    {
        $client = static::createClient();

        $client->request('GET', '/api/users');
        $this->assertResponseIsSuccessful();
        $allUsers = json_decode($client->getResponse()->getContent(), true);

        $randomUser = $allUsers[array_rand($allUsers)];

        $client->request('DELETE', '/api/users/'.$randomUser['id']);
        $this->assertResponseIsSuccessful();
        $response = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(['message' => 'User deleted'],$response);
    }
}