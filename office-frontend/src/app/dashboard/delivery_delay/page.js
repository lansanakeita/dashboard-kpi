"use client";

import React, { useEffect, useState } from 'react';
import { getDaytimeDeliveriesRate, getDeliveriesRate, getDeliveriesTrends } from '@/app/_api/delivery/delivery.api.js';

import ArrowBackIosIcon from '@mui/icons-material/ArrowBackIos';
import ArrowForwardIosIcon from '@mui/icons-material/ArrowForwardIos';
import BarGraph from '@/app/_graph/BarGraph';
import Box from "@mui/material/Box";
import CircularProgress from '@mui/material/CircularProgress';
import Grid from '@mui/material/Grid';
import IconButton from '@mui/material/IconButton';
import LeftNav from "@/components/LeftNav";
import LineGraph from '@/app/_graph/LineGraph';
import PieChart from "@/app/_graph/PieChart";
import { ThemeProvider } from "@mui/material/styles";
import Typography from '@mui/material/Typography';
import get from 'lodash/get.js';
import { theme } from "../../theme";

export default function DeliveryDelay() {
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState(null);
  const [pieChartData, setPieChartData] = useState(null);
  const [barGraphData, setBarGraphData] = useState(null);
  const [lineGraphData, setLineGraphData] = useState(null);
  const [currentDate, setCurrentDate] = useState(new Date());

  useEffect(() => {
    async function fetchDashboardeliveryDelay() {
      const startDate = new Date(2023, 0, 31);
      const endDate = new Date(2024, 0, 11);

      const start = startDate.toISOString().split('T')[0];
      const end = endDate.toISOString().split('T')[0];
      
      try {
        const pieChartDataResponse = await getDaytimeDeliveriesRate();
        const barGraphResponse = await getDeliveriesTrends(start, end);
        const lineGraphResponse = await getDeliveriesRate();

        const pieChartData = get(pieChartDataResponse, 'dayTimeDelivery') || {};
        const barGraphData = get(barGraphResponse, 'deliveriesTrends') || {};
        const lineGraphData = get(lineGraphResponse, 'deliveryRates[0]') || {};
        console.log("🚀 ~ fetchDashboardeliveryDelay ~ lineGraphData:", lineGraphData)

        setPieChartData(pieChartData);
        setBarGraphData(barGraphData);
        setLineGraphData(lineGraphData);
        
      } catch (err) {
        console.error("Erreur lors du chargement des données:", err);
        setError(err.toString());
      } finally {
        setIsLoading(false);
      }
    }

    fetchDashboardeliveryDelay();
  }, []);
  
  return (
    <ThemeProvider theme={theme}>
      <div>
        <LeftNav>
          <Box
            display="flex"
            justifyContent="space-between"
            gap={3}
            flexWrap="wrap"
          >
            {isLoading ? (
              <Grid
                container direction="column"
                justifyContent="center"
                alignItems="center"
                style={{ minHeight: '100vh' }}
              >
                <CircularProgress />
              </Grid>
            ) : null}

            {error ? (
              <Grid container direction="column" justifyContent="center" alignItems="center" style={{ minHeight: '100vh' }}>
                <Typography variant="h6">Une erreur s'est produite :</Typography>
                <Typography variant="body1">{ error }</Typography>
              </Grid>
            ) : null}

            {pieChartData && <PieChart data={pieChartData} title="Taux de livraison effectués par jour et par nuit" />}
            
            {barGraphData && <BarGraph data={barGraphData} title="Tendance des livraisons"/>}

            {lineGraphData && <LineGraph data={lineGraphData} title="Tendance des livraisons"/>}
          </Box>
        </LeftNav>
      </div>
    </ThemeProvider>
    );
}