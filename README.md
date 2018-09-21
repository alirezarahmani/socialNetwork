
Social Network Home Excise:
-

Hello

Introduction:
--
I did this base on Domain-Driven Design approach. I use [**CQRS**](http://docs.getprooph.org/tutorial/introduction.html#1-1-4) ([**Event Sourcing**](http://docs.getprooph.org/event-store/)) architecture for the only bounded context in the project. I prefer to use MYSQL as write storage and [memcacheD as read storage](https://www.youtube.com/watch?v=UH7wkvcf0ys).

How To Start:
---
The only thing you need to start project is just run the following command: `./build.sh` in the root of project. 

Important Notes:
---
 - As in the excise description mentioned, in this project I assumed we are in sunny day. So, You do not find any validation(I write some validation only for business rules)
 - I do not use any Framework, Library or Modules; But I use some PHP packages(As I got your permission in Linkedin). These packages have 99% percentage test coverage and large community.
 
How Does it work:
---
 list of commands(as I use docker, the prefix of all commands is `docker-compose exec worker php console`) sample commands:
  - Post: `alireza - 'Hello guys'`
  - Read: `alireza` 
  - Follow: `anton follows alireza` 
  - Wall: `alireza wall` 
