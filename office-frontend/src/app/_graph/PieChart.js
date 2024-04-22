"use client";

import { ArcElement, Chart as ChartJS, Legend, Tooltip } from 'chart.js';

import ChartDataLabels from 'chartjs-plugin-datalabels';
import { Pie } from 'react-chartjs-2';
import React from 'react';

ChartJS.register(ArcElement, Tooltip, Legend, ChartDataLabels);

function PieChart({ data, title }) {
  const chartData = {
    labels: Object.keys(data),
    datasets: [
      {
        label: 'Taux de Livraison',
        data: Object.values(data),
        backgroundColor: ['#57CC99', '#22577A'],
        hoverOffset: 4
      }
    ]
  };

  const options = {
    plugins: {
      datalabels: {
        color: '#fff',
        formatter: (value) => value.toFixed(2) + '%',
      },
      legend: {
        position: 'top',
      },
      title: {
        display: true,
        text: title,
        font: {
          size: 12
        }
      }
    }
  };

  return (
    <div style={{ width: '400px', height: '400px' }}>
      <Pie data={chartData} options={options} />
    </div>
  );
}

export default PieChart;

