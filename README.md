# Simple Symfony API

This is a simple API with CRUD operations on an entity, in this example a User.


## Install
Run the following command in the terminal:

    docker-compose up -d --build


Install dependencies:
    
    docker exec php bash -c "composer update"

Initialize the database:
    
    docker exec php bash -c "symfony console doctrine:migrations:migrate -n"

(Optional) Load the fixtures:

    docker exec php bash -c "symfony console doctrine:fixtures:load -n"


The service should now be available through http://localhost:8000/api

# Endpoints

## Get list of Users

### Request

`GET /api/users`

    curl http://localhost:8080/api/users -H "Content-Type: application/json"

### Response

       HTTP/1.1 200 OK
    
       [
            {
                   "id":1,
                   "first_name":"Foo",
                   "last_name":"bar",
                   "phone_number":"+351 912 345 678",
                   "email": "email@email.com",
                   "address": "Fake Street 123"
               }
       ]

## Create a new User

### Request

`POST /api/users`

    curl -X POST http://localhost:8080/api/users -H "Content-Type: application/json" -d "{ \"first_name\": \"Foo\", \"last_name\": \"Bar\", \"phone_number\": \"+351 912 345 678\", \"address\": \"Fake Street\", \"email\": \"email@email.com\" }"

### Response

    HTTP/1.1 201 Created

    {
       "id":1,
       "first_name":"Foo",
       "last_name":"bar",
       "phone_number":"+351 912 345 678",
       "email": "email@email.com",
       "address": "Fake Street 123"
   }

## Get a specific User

### Request

`GET /api/users/{id}`

    curl http://localhost:8080/api/users/1 -H "Content-Type: application/json"

### Response

    HTTP/1.1 200 OK

    {
       "id":1,
       "first_name":"Foo",
       "last_name":"bar",
       "phone_number":"+351 912 345 678",
       "email": "email@email.com",
       "address": "Fake Street 123"
   }

## Get a non-existent User

### Request

`GET /api/users/{id}`

    curl http://localhost:8080/api/users/-1 -H "Content-Type: application/json"

### Response

    HTTP/1.1 404 Not Found

    {"message": "User not found"}
   

## Attempt to change a User using partial params

### Request

`PATCH /api/users/{id}`

    curl -X PATCH http://localhost:8080/api/users/1 -H "Content-Type: application/json" -d "{ \"first_name\": \"Not Foo\" }"


### Response

    HTTP/1.1 200 OK
    
        {
           "id":1,
           "first_name":"Not Foo",
           "last_name":"bar",
           "phone_number":"+351 912 345 678",
           "email": "email@email.com",
           "address": "Fake Street 123"
       }

## Attempt to change a User using invalid params

### Request

`PATCH /api/users/{id}`

    curl -X PATCH http://localhost:8080/api/users/1 -H "Content-Type: application/json" -d "{ \"not_first_name\": \"Not Foo\" }"

### Response

    HTTP/1.1 400 Bad Request

    {"message": "Error processing request"}
    

## Delete a User

### Request

`DELETE /api/users/{id}`

    curl -X DELETE http://localhost:8080/api/users/1 -H "Content-Type: application/json"

### Response

   
    HTTP/1.1 200 OK
    
    {"message": "User deleted"}


## Try to delete same User again

### Request

`DELETE /api/users/{id}`

    curl -X DELETE http://localhost:8080/api/users/1 -H "Content-Type: application/json"

### Response

    HTTP/1.1 404 Not Found

    {"message": "User not found"}



#Testing

Setup test database
    
     docker exec php bash -c "php bin/console --env=test doctrine:database:create"
     docker exec php bash -c "php bin/console --env=test doctrine:schema:create"


Run tests

    docker exec php bash -c "./vendor/bin/simple-phpunit"
