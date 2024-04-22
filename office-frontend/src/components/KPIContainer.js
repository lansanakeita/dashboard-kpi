"use client";
import Box from "@mui/material/Box";
import Typography from "@mui/material/Typography";

export default function KPIContainer({ title, kpi }) {
  return (
    <Box
      sx={{
        boxShadow: 3,
        borderRadius: 2,
        bgcolor: "white",
        p: 5,
        margin: "auto",
        display: "flex",
        flexDirection: "column",
        alignItems: "center",
        justifyContent: "center",
      }}
    >
      <Typography variant="body1" align="center" color="#8D95A4">
        {title}
      </Typography>
      <Typography variant="subtitle1" align="center" color="primary.main">
        {kpi}
      </Typography>
    </Box>
  );
}
