const ctx1 = document.getElementById('chart1');

const labelsStr1 = ctx1.parentElement.dataset.names;
const labelsArr1 = labelsStr1.split(', ');

const dataStr1 = ctx1.parentElement.dataset.info;
const dataArr1 = dataStr1.split(', ');

new Chart(ctx1, {
  type: 'pie',
  data: {
    labels: labelsArr1,
    datasets: [{
      label: 'Количество в категории',
      data: dataArr1,
    }]
  }
});

const ctx2 = document.getElementById('chart2');

const labelsStr2 = ctx2.parentElement.dataset.names;
const labelsArr2 = labelsStr2.split(', ');

const dataStr2 = ctx2.parentElement.dataset.info;
const dataArr2 = dataStr2.split(', ');

new Chart(ctx2, {
  type: 'pie',
  data: {
    labels: labelsArr2,
    datasets: [{
      label: 'Количество со статусом',
      data: dataArr2,
    }]
  }
});

const ctx5 = document.getElementById('chart5');

const labelsStr5 = ctx5.parentElement.dataset.names;
const labelsArr5 = labelsStr5.split(', ');

const dataStr5 = ctx5.parentElement.dataset.info;
const dataArr5 = dataStr5.split(', ');

new Chart(ctx5, {
  type: 'doughnut',
  data: {
    labels: labelsArr5,
    datasets: [{
      label: 'Количество со статусом',
      data: dataArr5,
    }]
  }
});

const ctx3 = document.getElementById('chart3');
createChartBar(ctx3, 'Количество');

const ctx4 = document.getElementById('chart4');
createChartBar(ctx4, 'Заработано');

const ctx6 = document.getElementById('chart6');
createChartBar(ctx6, 'Средняя оценка');

const ctx7 = document.getElementById('chart7');
createChartBar(ctx7, 'Количество');

function createChartBar(ctx, label) {

  const titleStr = ctx.parentElement.dataset.names;
  const titleArr = titleStr.split(', ');

  const idStr = ctx.parentElement.dataset.id;
  const idArr = idStr.split(', ');

  let labelsArr = [];

  for(let i = 0; i < titleArr.length; i++) {
    let str;
    if(titleArr[i].length > 15){
      str = "id: " + idArr[i] + " (" + titleArr[i].slice(0, 15) + "...)" ;
    }
    else {
      str = "id: " + idArr[i] + " (" + titleArr[i] + ")";
    }
    labelsArr.push(str);
  }

  const dataStr = ctx.parentElement.dataset.info;
  const dataArr = dataStr.split(', ');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labelsArr,
      datasets: [{
        label: label,
        data: dataArr,
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
          'rgba(255, 159, 64, 0.2)',
          'rgba(255, 205, 86, 0.2)',
          'rgba(75, 192, 192, 0.2)',
          'rgba(54, 162, 235, 0.2)'
        ],
        borderColor: [
          'rgb(255, 99, 132)',
          'rgb(255, 159, 64)',
          'rgb(255, 205, 86)',
          'rgb(75, 192, 192)',
          'rgb(54, 162, 235)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    },
  });

}

const ctx8 = document.getElementById('chart8');

const labelsStr8 = ctx8.parentElement.dataset.names;
const labelsArr8 = labelsStr8.split(', ');

const dataStr8 = ctx8.parentElement.dataset.info;
const dataArr8 = dataStr8.split(', ');

new Chart(ctx8, {
  type: 'doughnut',
  data: {
    labels: labelsArr8,
    datasets: [{
      label: 'Количество',
      data: dataArr8,
    }]
  }
});

const ctx9 = document.getElementById('chart9');

const labelsStr9 = ctx9.parentElement.dataset.names;
const labelsArr9 = labelsStr9.split(', ');

const dataStr9 = ctx9.parentElement.dataset.info;
const dataArr9 = dataStr9.split(', ');

new Chart(ctx9, {
  type: 'pie',
  data: {
    labels: labelsArr9,
    datasets: [{
      label: 'Количество',
      data: dataArr9,
    }]
  }
});

const ctx10 = document.getElementById('chart10');

const labelsStr10 = ctx10.parentElement.dataset.names;
const labelsArr10 = labelsStr10.split(', ');

const dataStr10 = ctx10.parentElement.dataset.info;
const dataArr10 = dataStr10.split(', ');

new Chart(ctx10, {
  type: 'bar',
  data: {
    labels: labelsArr10,
    datasets: [{
      label: 'Количество',
      data: dataArr10,
      backgroundColor: [
        'rgba(255, 99, 132, 0.2)',
        'rgba(255, 159, 64, 0.2)',
        'rgba(255, 205, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)',
        'rgba(54, 162, 235, 0.2)'
      ],
      borderColor: [
        'rgb(255, 99, 132)',
        'rgb(255, 159, 64)',
        'rgb(255, 205, 86)',
        'rgb(75, 192, 192)',
        'rgb(54, 162, 235)'
      ],
      borderWidth: 1
    }]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  },
});
