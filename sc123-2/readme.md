# Code Challenges

This directory should contain four files:

- tables.html
- request.php
- model.php
- this readme

There are two challenges, both explained below. When you're done, zip and send us the updated files. And of course let us know of any issues.

## UI Sorting

The file `tables.html` contains a table. You can open the file in your browser to see it rendered.

This challenge is to make the table sortable. With vanilla Javascript, make the headers clickable, and have the click action toggle the sort order of the values in their column. You can change the HTML in any way you'd like but leave the displayed values as they are.

With this challenge we're assessing your general problem-solving techniques, sensitivity to UI/UX, writing style, etc.

Try to write the script in such a way that it could be reused on other tables with no modification. Bonus points for making the table look super cool.

## Request Routing

The files `request.php` and `model.php` are modified versions of files in use in production. They're both part of the Admins module. The purpose of the Request class is to receive and handle requests to this module from a central request router, and the purpose of the Model class is to mediate between the Request class and the database. Architecturally, a request's lifecycle looks something like:

    client
     |  ^
     v  |
    central request router
     |  ^
     v  |
    module request router <--> module view-builder
     |  ^
     v  |   
    module model-maker
     |  ^
     v  |
    database

As an example, consider the request `GET /admins/123`. The `GET` specifies the HTTP verb---essentially, it's what the user wants to do with the request. The URI contains two parts. We call the 0th part (`admins`) the "controller part", and it specifies the module that receives the request. We call the 1st part (`123`) and any others that might follow the "query parts", and they specify what the user is requesting. In this case, the client is requesting a detailed view of the Admin with the ID 123.

The central request router (1) inspects the HTTP verb and the URI's controller part, (2) checks if the corresponding module's Request class can handle a request of that type (by checking for a function named "handle" + the verb, e.g. `handleGet`), and (3) routes or responds accordingly. The module's request class then inspects the request's query parts, validates them, checks user permissions, etc., and responds in an appropriate way.

Most modules handle four HTTP verbs: get, post, patch, and delete.

This challenge is to implement the Request class's `handleDelete` function.

You won't be able to run this code, but don't worry about that. We're not looking for executable or even correct code---all we're assessing with this challenge is how you read, navigate, write into, and in general respond to an unfamiliar codebase.

Because these files are modified copies from production, they're untested and there may be some errors or inconsistencies. If you notice any, leave a comment on the relevant line.
