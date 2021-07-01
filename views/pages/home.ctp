  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
  <style type="text/css">
  div.green { margin: 0px; width: 100px; height: 80px; background: green; border: 1px solid black; position: relative; }
  div.red { margin-top: 10px; width: 50px; height: 30px; background: red; border: 1px solid black; position: relative; }
  .ui-effects-transfer { border: 2px solid black; }

</style>

  
<body style="font-size:62.5%;">
  <div class="green"></div>
<div class="red"></div>

</body>
</html>

<script>
  $(document).ready(function() {
    
$("div").click(function () {
      var i = 1 - $("div").index(this);
      $(this).effect("transfer", { to: $("div").eq(i) }, 1000);
});

  });
  </script>