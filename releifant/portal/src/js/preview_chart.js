var xValues;
var yValues;

function getDateValues() {
  const x_values_string = document.querySelector("[name=chart_data_x]").content;
  const y_values_string = document.querySelector("[name=chart_data_y]").content;

  document.querySelector("[name=chart_data_x]").remove();
  document.querySelector("[name=chart_data_y]").remove();

  xValues = x_values_string.split(" ");
  yValues = y_values_string.split(" ");
  xValues = xValues.slice(0, -1);
  yValues = yValues.slice(0, -1);
}

function valuesToDates() {
  var day, month, year;
  for (var i = 0; i < xValues.length; i++) {
    day = parseInt(xValues[i].slice(8, 10));
    month = parseInt(xValues[i].slice(5, 7)) - 1;
    year = parseInt(xValues[i].slice(0, 4));
    xValues[i] = new Date(year, month, day);
  }
}

function show_chart() {
  getDateValues();
  valuesToDates();

  google.charts.load("current", {
    packages: ["corechart"],
  });
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var data = new google.visualization.DataTable();
    data.addColumn("date", "Time of Day");
    data.addColumn("number", "Rating");

    for (var i = 0; i < xValues.length; i++) {
      data.addRows([[xValues[i], parseInt(yValues[i])]]);
    }

    var options = {
      title: "Pain level from 1 to 10",
      legend: { position: "none" },
      pointSize: 7,
      width: 730,
      height: 280,
      backgroundColor: { fill: "transparent" },
      hAxis: {
        // format: "dd MMM yyyy",
        format: "MMM yyyy",
        gridlines: {
          count: 15,
        },
      },
      vAxis: {
        gridlines: {
          color: "none",
        },
        minValue: 0,
        maxValue: 10,
      },
    };

    var chart = new google.visualization.LineChart(
      document.getElementById("chart_div")
    );

    chart.draw(data, options);
  }
}
