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

//========================================================
// Im struggling with how to treat the file as a database.
// do i set up my own local database and make PHP calls to it?
// or am i pseudo-pseudo coding a solution involving the .csv?
  // $myfile = fopen('/contacts.csv', 'r+');
  // echo $myfile;
//========================================================
// See contacts.sql for database


//========================================================
// Going to build out the function to handle the cases based on their route first
function handle_request($method, $path, $test=null){
  // Only two options at this point are GET and POST (add edge case for errors)
  switch ($method) {
    case 'get':
      echo "--> Get: $method at path $path \n";
      // $_SERVER["GET"];       ??????????
      // return json_encode(<<response>>)
      break;
    case 'post':
      // If there's no data with the post request, throw error
      if(!$test['data']){
        echo "Error: Post requests require data";
      } else {
        echo "--> Post: $method at path $path with valid data\n";
        // return json_encode(<<response>>)
      }
      break;
    default:
      echo "Error: Invalid method \n";
      // return json_encode(<<response>>)
      break;
  }
}

// foreach ($tests as $test) {
//     echo "Request: ".$test['method']." ".$test['path']."\n";
//     $response = handle_request($test['method'] , $test['path'], $test);
//     echo "Response:\n".print_r($response, true)."\n";
// }
