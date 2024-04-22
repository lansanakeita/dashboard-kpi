"use client";

import { BarElement, CategoryScale, Chart as ChartJS, Legend, LinearScale, PointElement, Title, Tooltip } from 'chart.js';

import { Bar } from 'react-chartjs-2';
import React from 'react';

ChartJS.register(CategoryScale, LinearScale, PointElement, BarElement, Title, Tooltip, Legend);

const BarGraph = ({ data }) => {
  const chartData = {
    labels: ['Livraisons en Retard', 'Livraisons à l’Heure', 'Livraisons en Avance'],
    datasets: [
      {
        label: 'Livraisons en Retard',
        data: [data.lateDeliveries, 0, 0], // Only the first position is filled
        borderColor: 'rgb(255, 99, 132)',
        backgroundColor: 'rgba(255, 99, 132, 0.5)',
      },
      {
        label: 'Livraisons à l’Heure',
        data: [0, data.onTimeDeliveries, 0], // Only the second position is filled
        borderColor: '#22577A',
        backgroundColor: '#22577A',
      },
      {
        label: 'Livraisons en Avance',
        data: [0, 0, data.earlyDeliveries], // Only the third position is filled
        borderColor: '#57CC99',
        backgroundColor: '#57CC99',
      },
    ],
  };

  const options = {
    maintainAspectRatio: false,
    scales: {
      x: {
        stacked: true,
      },
      y: {
        stacked: true
      }
    },
    plugins: {
      title: {
        display: true,
        text: 'Tendances des Livraisons',
      },
    },
  };

  return (
    <div style={{ height: '400px', width: '600px' }}>
      <Bar data={chartData} options={options} />
    </div>
  );
};

export default BarGraph;
