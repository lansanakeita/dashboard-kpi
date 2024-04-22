"use client";

import React, { useEffect, useState } from 'react';

import Box from "@mui/material/Box";
import CircularProgress from '@mui/material/CircularProgress';
import Grid from '@mui/material/Grid';
import KPIContainer from "@/components/KPIContainer";
import LeftNav from "@/components/LeftNav";
import { ThemeProvider } from "@mui/material/styles";
import Typography from '@mui/material/Typography';
import get from 'lodash/get.js';
import { getAverageRating } from '@/app/_api/satisfaction/satisfaction.api.js';
import { getRecommendedRate } from '@/app/_api/recommandation/recommandation.api';
import { getReturnRate } from '@/app/_api/return/return.api.js';
import { theme } from "../theme";

export default function Dashboard() {
  const [rating, setRating] = useState(null);
  const [returnRate, setReturnRate] = useState(null);
  const [recommendedRate, setRecommendedRate] = useState(null);
  const [isLoading, setIsLoading] = useState(true);
  const [error, setError] = useState(null);
  
  useEffect(() => {
    async function fetchDashboardData() {
      try {
        const averageRatingResponse = await getAverageRating();
        const returnRateResponse = await getReturnRate();
        const recommendedRateResponse = await getRecommendedRate();

        const averageRating = get(averageRatingResponse, 'averageRating') || "N/A";
        const returnRate = get(returnRateResponse, 'returnRate') || "N/A";
        const recommendedRate = get(recommendedRateResponse, 'recommendedRate') || "N/A";

        setRating(averageRating);
        setReturnRate(returnRate);
        setRecommendedRate(recommendedRate);
        
      } catch (err) {
        console.error("Erreur lors du chargement des données:", err);
        setError(err.toString());
      } finally {
        setIsLoading(false);
      }
    }

    fetchDashboardData();
  }, []);

  if (isLoading) {
    return (
      <Grid
        container direction="column"
        justifyContent="center"
        alignItems="center"
        style={{ minHeight: '100vh' }}>
        <CircularProgress />
      </Grid>
    );
  }

  if (error) {
    return (
      <Grid container direction="column" justifyContent="center" alignItems="center" style={{ minHeight: '100vh' }}>
        <Typography variant="h6">Une erreur s'est produite :</Typography>
        <Typography variant="body1">{ error }</Typography>
      </Grid>
    );
  }
  
  return (
    <ThemeProvider theme={theme}>
      <div>
        <LeftNav>
          <Box
            display="flex"
            justifyContent="space-between"
            gap={2}
            flexWrap="wrap"
          >
            <KPIContainer title="Note de satisfaction moyenne" kpi={`${rating} / 5`} />
            <KPIContainer title="Taux de retours" kpi={`${returnRate} %`} />
            <KPIContainer title="Taux de recommandation" kpi={`${recommendedRate} %`} />
          </Box>
        </LeftNav>
      </div>
    </ThemeProvider>
  );
}
