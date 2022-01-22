<?php
/*
  Challenge: make a request router.

  The variable `$tests` contains subarrays, each of which represents a
  request. The `method` represents the HTTP verb:
    https://developer.mozilla.org/en-US/docs/Web/HTTP/Methods
  And the `path` represents the URL being requested.

  The `get` verb means: show me the thing specified by the `path`, and
  the `post` verb means: create an entry of the type specified by the
  `path` with the given `data`.

  For this challenge, design and implement a request-handling system.
  It should receive the `method`, the `path`, and optionally the `data`,
  and it should return an appropriate JSON representation of the thing
  being requested.

  You can run this challenge like so:
    $ php contacts-requests.php
  Just make sure PHP is installed and the file `contacts.csv`, which
  represents the database, is accessible.

  When you're happy with it, send us a zip file containing this file,
  the contacts database, and the file(s) you add, so we can run it.
*/
$tests = [
    // Should return all the contacts.
    ['method' => 'get', 'path' => '/contacts'],
    // Should return the contact with the ID 2.
    ['method' => 'get', 'path' => '/contacts/2'],
    // Should return the contact with the randomly-selected ID.
    ['method' => 'get', 'path' => '/contacts/'.rand(1, 3)],
    // Should return an error message.
    ['method' => 'get', 'path' => '/contacts/9000'],
    // Should return an error message.
    ['method' => 'get', 'path' => '/foo/bar'],
    // Should save the new contact to the database.
    ['method' => 'post', 'path' => '/contacts', 'data' => [
        'name' => 'Kevin Mitnick', 'role' => 'SuperHacker'
    ]],
    // Should return the newly-created entry.
    ['method' => 'get', 'path' => '/contacts/4'],
    // Should return an error.
    ['method' => 'post', 'path' => '/contacts', 'data' => [
        'foo' => 'Oh', 'bar' => 'No'
    ]],
    // Should return the updated list of contacts.
    ['method' => 'get', 'path' => '/contacts'],
];
foreach ($tests as $test) {
    echo "Request: ".$test['method']." ".$test['path']."\n";
    $response = CALL_YOUR_FUNCTION_HERE;
    echo "Response:\n".print_r($response, true)."\n";
}
