google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawCharts);

function drawCharts() {
  drawCityChart();
  drawCountryChart();
}

function drawCityChart() {
  cityArray = [['City', 'Visit Count']];
  var i = 1;
  var city = document.getElementById('top-city-' + i);
  while (city != null) {
    i++;
    cityArray.push([city.innerText, parseInt(city.getAttribute('popularity'))]);
    city = document.getElementById('top-city-' + i);
  }
  var data = google.visualization.arrayToDataTable(cityArray);
  var options = {
    title: 'Most visited cities of the month',
    chartArea: {width: '50%'},
    hAxis: {
      minValue: 0
    }
  };
  var chart = new google.visualization.BarChart(document.getElementById('city_chart_div'));
  chart.draw(data, options);
}

function drawCountryChart() {
  countryArray = [['Country', 'Revenue (TL)']];
  var i = 1;
  var country = document.getElementById('top-country-' + i);
  while (country != null) {
    i++;
    countryArray.push([country.innerText, parseInt(country.getAttribute('revenue'))]);
    country = document.getElementById('top-country-' + i);
  }
  var data = google.visualization.arrayToDataTable(countryArray);
  var options = {
    title: 'Top revenue-making countries of the year',
    chartArea: {width: '50%'},
    hAxis: {
      minValue: 0
    }
  };
  var chart = new google.visualization.BarChart(document.getElementById('country_chart_div'));
  chart.draw(data, options);
}
