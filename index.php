<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <title>sql-injection-sample</title>
  <script>
  function showtables()
  {
    $.ajax({
			type: "POST",
			url: "ajax_sql_select_all.php",
			success: function (rtndata) {
        for (var i = 0; i < rtndata.tables.length; ++i) {
          var table_name = Object.keys(rtndata.tables)[i];
          //heading
          $("#tablesdiv").append('<h3>'+table_name+'</h3>');
          $("#tablesdiv").append('<table id="tables_'+table_name+'">');
          //thead
          $("#tablesdiv").append('<thead><tr>');
          for (var j = 0; j < rtndata.tables[i][0].length; j++) {
            $("#tablesdiv").append('<th>'+Object.keys(rtndata.tables[i][0])[j]+'</th>');
          }
          $("#tablesdiv").append('</tr></thead>');
          //tbody
          $("#tablesdiv").append('<tbody>');
          for (var j = 0; j < rtndata.tables[i].length; j++) {
            $("#tablesdiv").append('<tr>');
            for (var k = 0; k < rtndata.tables[i][j].length; k++) {
              $("#tablesdiv").append('<td>'+rtndata.tables[i][j][k]+'</td>');
            }
            $("#tablesdiv").append('</tr>');
          }
          $("#tablesdiv").append('</tbody>');
          //close
          $("#tablesdiv").append('</table>');
        }
		}
  });
}
  $("#submit").click(function(e) {
    var submit_url = "";
    switch ($('input[name=protection]:checked').val() )
    {
      case "weak":
        submit_url = "ajax_sql_weak.php";
        break;
      case "sanitize":
        submit_url = "ajax_sql_sanitize.php";
        break;
      case "prepared":
        submit_url = "ajax_sql_prepared.php";
        break;
      default:
        return;
        break;
    }
    $("#submit").text( "Submiting...");
		$("#submit").attr('disabled',true);
		$.ajax({
			type: "POST",
			url: submit_url,
			success: function (rtndata) {
				if (rtndata.action == 1)
				{
					$("#submit_status").text('success');
					$("#submit").attr('disabled',false);
				}
				else
				{
					$("#submit_status").text('' + rtndata.data);
					$("#submit").attr('disabled',false);
				}
			},
			failure: function (rtndata) {
				//$("#submit_status").text("Connection Error");
				$("#submit").attr('disabled',false);
			}
		});
		$("#submit").text("Submit");
  });//submit click
  $("#resetdb").click(function(e) {
    $("#resetdb").text( "Reseting...");
		$("#resetdb").attr('disabled',true);
		$.ajax({
			type: "POST",
			url: "ajax_reset_database.php",
			success: function (rtndata) {
				if (rtndata.action == 1)
				{
					$("#resetdb_status").text('success');
					$("#resetdb").attr('disabled',false);
				}
				else
				{
					$("#resetdb_status").text('' + rtndata.data);
					$("#resetdb").attr('disabled',false);
				}
			},
			failure: function (rtndata) {
				//$("#resetdb_status").text("Connection Error");
				$("#resetdb").attr('disabled',false);
			}
		});
		$("#resetdb").text("Reset Database");
  });//reset click
  $("#updatetables").click(function (e) {
    $("#updatetables").attr('disabled',true);
    showtables();
    $("#updatetables").attr('disabled',false);
  });//show tables click
  </script>
</head>
<body>
  <div>
    <p>
      situation: pretend this box was an ID lookup. Enter number, get text related to number.
      This table is supposed to only use a <code>SELECT</code> statement.
    </p>
    <p>
      textinput area is large to make it easier to inject.
    </p>
    <ul>
      <li>Postgresql</li>
      <li>PHP PDO</li>
    </ul>
    <ul>
      <li>weak: takes input and directly puts it into sql statement</li>
      <li>prepared: uses prepared sql statements. (via PHP PDO)</li>
      <li>sanitized: filters input string for certain characters.</li>
    </ul>
  </div>
  <div>
    <h2>reset</h2>
    <button id="resetdb">Reset Database</button>
  </div>
  <div>
        Enter number to search:<textarea name="input" maxlength="500" cols="20" rows="5"></textarea>
        <br>
          <fieldset id="protection">
            <input type="radio" value="weak" name="protection" id="radio_weak" CHECKED>
            <label for="radio_weak">Weak</label><br>
            <input type="radio" value="prepared" name="protection" id="radio_prepared">
            <label for="radio_prepared">Prepared</label><br>
            <input type="radio" value="sanitize" name="protection" id="radio_sanitize" DISABLED>
            <label for="radio_sanitize">Sanitize</label><br>
          </fieldset>
        <br>
        <button name="submit" value="Submit" id="submit">Submit</button>
  </div>
  <div>
    <h2>contents</h2>
    <button id="updatetables">Update Tables</button>
    <div id="tablesdiv">
    </div>
  </div>
</body>
</html>
