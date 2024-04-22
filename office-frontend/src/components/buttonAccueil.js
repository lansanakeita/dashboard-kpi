import * as React from 'react';
import Button from '@mui/material/Button';
import { ThemeProvider } from "@mui/material/styles";
import { theme } from "../app/theme";

export default function ButtonUsage({ buttonText }) {
  return (
    <ThemeProvider theme={theme}>
      <Button 
        style={{
          backgroundColor: theme.palette.secondary.main,
          color: theme.palette.primary.main,
          width: '391px',
        }}
        variant="contained"
      >
        {buttonText}
      </Button>
    </ThemeProvider>
  );
}
