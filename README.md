
Social Network Home Excise:
-

Hello

Introduction:
--
I did this base on Domain-Driven Design approach. I use [**CQRS**](http://docs.getprooph.org/tutorial/introduction.html#1-1-4) ([**Event Sourcing**](http://docs.getprooph.org/event-store/)) architecture for the only bounded context in the project. I prefer to use MYSQL as write storage and [memcacheD as read storage](https://www.youtube.com/watch?v=UH7wkvcf0ys). I also created 3 memcached servers in dockerFile, I assume, I will store million records in memcached. 

How To Start:
---
The only thing you need to run project is just run the following command: `./build.sh` in the root of project. 

Important Notes:
---
 - As in the excise description mentioned, in this project I assumed we are in sunny day(all inputs are right). So, You do not find any validation(I write some validation only for business rules)
 - I do not use any Framework, Library or Modules; But I use some PHP packages(**As I got your permission in Linkedin**). These packages have 99% percentage test coverage and large community, thousands people trust them.

How Does it work:
---
first run projections: `docker-compose exec worker php console run:timeline:projection` . After, run commands below.
 
 list of commands(as I use docker and [symfony console](https://symfony.com/doc/2.6/cookbook/console/console_command.html) package, we have some prefixes to execute commands. the prefix of all commands is `docker-compose exec worker php console`) sample commands:
  - To Post: `post alireza - 'Hello guys'` 
  - To Read: `read alireza` 
  - To Follow: `follow anton follows alireza` 
  - To Wall: `wall alireza wall` 
  
You can easily add new command, just create a command in `Infrastructure/Cli` directory. It will be automatically added to project.

Tests:
---
I almost write test for everything. You can simply run tests with: `docker-compose exec worker php vendor/phpunit/phpunit/phpunit ` .
 
What Are Missing:
---
 - no validation according to inputs of commands, as we assume all commands are correct.
 - comprehensive in memory Service. There is no such a great Service to save or read records in memcached. I just created a simple index base class, according to requirement of this project.
 - value objects, factories or even a separate entity. according to this project, I do not see any necessity. In some case, I afraid if do over engineering.
 - I might create separate projections for each events. But according to requirements of this project I think the current implementation is enough.
 
 > Why instead of composer packages I do not write them? As I prefer DDD approach writing and testing that amount of code takes too much time.

 > If you do care I would be glad to implement missing parts.
 
 Thanks in Advance.

