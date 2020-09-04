<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>

  </body>

  <script type="text/javascript">

  success = false;

  let promise = new Promise(function(resolve, reject) {
  // executor (the producing code, "singer")

  //returns resolve or reject string
  resolve();
  });

  promise.then(
    function(resolve){console.log('resolved is: '+typeof(resolve)); return resolve;},

    function(error){console.log('error');}

  ).then(
    passed=>
    //what is being passed?
    console.log("passed is: "+passed)

  );



  // var promise = fetch('res.txt');


  // promise.then(function(res) {console.log(res)});

  </script>

</html>
