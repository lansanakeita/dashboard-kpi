import Button from "@mui/material/Button";
import { ThemeProvider } from "@mui/material/styles";
import { theme } from "../app/theme";

export default function ButtonTest({ buttonText, onClick }) {
  return (
    <ThemeProvider theme={theme}>
      <Button
        style={{
          backgroundColor: theme.palette.secondary.main,
          color: theme.palette.primary.main,
        }}
        variant="contained"
        onClick={onClick}
        className="w-full inline-flex justify-center items-center gap-2.5 p-2.5 p-2 rounded bg-[#57cc99] text-[#22577a] font-['Roboto'] font-medium leading-[normal]"
      >
        {buttonText}
      </Button>
    </ThemeProvider>
  );
}
