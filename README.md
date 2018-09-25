
Social Network Home Excise:
-

Hello

Introduction:
--
I select `Social Networking Kata`, I did it base on Domain-Driven Design approach. I use [**CQRS**](http://docs.getprooph.org/tutorial/introduction.html#1-1-4) ([**Event Sourcing**](http://docs.getprooph.org/event-store/)) architecture for the only bounded context in the project. I prefer to use MYSQL as write storage and [memcacheD as read storage](https://www.youtube.com/watch?v=UH7wkvcf0ys). I also created 3 memcached servers in dockerFile, I assume, I will store million records in memcached. 

How To Start:
---
The only thing you need to run project is just run the following command: `./build.sh` in the root of project. 

Important Notes:
---
 - As in the excise description mentioned, in this project I assumed we are in sunny day(all inputs are right). So, You do not find any validation(I write some validation only for business rules)
 - I do not use any Framework, Library or Modules; But I use some PHP packages(**As I got your permission in Linkedin**). These packages have 99% percentage test coverage and large community, thousands people trust them. Why instead of composer packages I do not write them? As I prefer DDD approach for this project writing and testing that amount of code takes too much time.


How Does it work:
---
first run projections: `docker-compose exec worker php console run:timeline:projection` . While projection is running, run commands below.
 
 list of commands(as I use docker and [symfony console](https://symfony.com/doc/2.6/cookbook/console/console_command.html) package, we have some prefixes to execute commands. the prefix of all commands is `docker-compose exec worker php console`) sample commands:
  - To Post: `post alireza - 'Hello guys'` 
  - To Read: `read alireza` 
  - To Follow: `follow anton follows alireza` 
  - To Wall: `wall alireza` 

For example Alireza post something like:   
 ` docker-compose exec worker php console post alireza - 'Hello, morning morning' `
 
You can read alireza's posts (posts only added by alireza):
`docker-compose exec worker php console read alireza  `
 
and when alireza follow anton:
`docker-compose exec worker php console follow alireza follows anton`

in alireza's wall you cam see anton's posts and alireza's posts.
` docker-compose exec worker php console wall alireza `
  
Tests:
---
I almost write test for everything. You can simply run tests with: `docker-compose exec worker php vendor/phpunit/phpunit/phpunit ` .
 
What Are Missing:
---
 - no validation according to inputs of commands, as we assume all commands are correct.
 - no comprehensive in memory Service. There is no such a great Service to save or read records in memcached. I just created a simple index base class, according to requirement of this project.
 - no value objects, factories and other DDD definitions. according to this project, I do not see any necessity. In some case, I afraid if do over engineering.
 - I might create separate projections. But according to requirements of this project I think the current implementation is enough.
 
 Thanks in Advance.

