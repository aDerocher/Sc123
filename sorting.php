<?php
/*
  Challenge: sorting.

  This challenge was inspired by a real customer request.

  The variable `$names` contains a list of names used at the top level
  of a customer's file organization system. The customer is presented
  with a list of links containing these names which lead to lower levels
  of their hierarchy.

  The default sorting method is alphabetic descending. If these names
  were all named in a way that sorted nicely, there would be no issue.
  But sometimes they add years to the names, and in inconsistent places,
  and they want to be able to sort by the alphabetic part of the name, or
  by the year, and they want to be able to toggle the order of the sort.

  For this challenge, design a function (or family of functions) that
  enables such sorting. It should receive the `$names`, the sorting method
  ("alpha" or "date"), and the direction ("asc" or "desc"), and return a
  version of the `$names` sorted according to those rules in a way that
  would be appropriate in the UI.

  For example, `mySort($names, 'alpha', 'desc')` should return:
    [
      "Datsuns",
      "Chevrolets 2021",
      "2020 Chevrolets",
      "Chevrolets 2019",
      "2021 Cadillacs",
      "Buicks",
      "Audis 2018",
      "Audis",
    ]
  and `mySort($names, 'date', 'asc')` should return:
    [
      "Audis 2018",
      "Chevrolets 2019",
      "2020 Chevrolets",
      "2021 Cadillacs",
      "Chevrolets 2021",
      "Audis",
      "Buicks",
      "Datsuns",
    ]
  and `mySort($names, 'date', 'desc')` should return:
    [
      "Chevrolets 2021",
      "2021 Cadillacs",
      "2020 Chevrolets",
      "Chevrolets 2019",
      "Audis 2018",
      "Datsuns",
      "Buicks",
      "Audis",
    ]

    You can run this challenge like so:
      $ php sorting.php (date|alpha) (asc|desc)
    Just make sure PHP is installed.

    When you're happy with it, send us this file and any files you add
    so we can run it.
*/
$names = [
    "Audis",
    "Audis 2018",
    "Buicks",
    "2021 Cadillacs",
    "Chevrolets 2019",
    "2020 Chevrolets",
    "Chevrolets 2021",
    "Datsuns",
];

$sorts = ['alpha', 'date'];
$directions = ['asc', 'desc'];
$sort = $sorts[0];
$direction = $directions[0];

array_shift($argv);
foreach ($argv as $arg) {
    $arg = strtolower($arg);
    if (in_array($arg, $sorts)) {
        $sort = $arg;
    } else if (in_array($arg, $directions)) {
        $direction = $arg;
    } else {
        echo "Invalid argument: ".$arg."\n";
    }
}

function dynamicSorter($names, $sort, $direction) {
  echo "Sorting Now! => :\n";
  foreach ($names as $val) {
    echo "$val ";
  }
}

echo "Sorting by ".$sort." ".$direction.":\n";
print_r(
  dynamicSorter($names, $sort, $direction)  // TODO
);
