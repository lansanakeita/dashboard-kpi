"use client";
import { useState } from "react";
import * as React from "react";
import Box from "@mui/material/Box";
import Rating from "@mui/material/Rating";
import Stack from "@mui/material/Stack";

export default function HoverRating() {
  const [temperature, setTemperature] = useState("temperature-1");
  const [humidity, setHumidity] = useState("humidity-1");
  const [broken, setBroken] = useState("broken-1");

  const [value, setValue] = React.useState(2);
  const [value2, setValue2] = React.useState(1);

  return (
    <main className="flex min-h-screen flex-col items-center p-24 bg-white">
      <h1 className="text-[#22577a] font-['Arial'] text-4xl leading-[normal] mb-[60px]">
        Votre avis
      </h1>
      <div className="flex flex-col items-start">
        <div className="shadow-md shadow-[#00000040] flex flex-col items-start gap-7 pt-[1.5625rem] pr-[1.5625rem] pb-[1.5625rem] pl-[1.5625rem] w-[774px] rounded-md bg-neutral-50 mb-[30px]">
          <div className="flex items-center text-black font-['Roboto'] leading-[normal]">
            Êtes-vous satisfait de la rapidité de la livraison ?
            <div className="flex items-start ml-[33px]">
              <Stack spacing={1}>
                <Rating name="half-rating" defaultValue={1} precision={0.5} />
              </Stack>
            </div>
          </div>
        </div>

        <div className="shadow-md shadow-[#00000040] flex flex-col items-start gap-7 pt-[1.5625rem] pr-[1.5625rem] pb-[1.5625rem] pl-[1.5625rem] w-[774px] rounded-md bg-neutral-50 mb-[30px]">
          <div className="flex items-center text-black font-['Roboto'] leading-[normal]">
            Êtes-vous satisfait de votre expérience avec notre entreprise ?
            <div className="flex items-start ml-[33px]">
              <Box
                sx={{
                  "& > legend": { mt: 2 },
                }}
              >
                <Rating
                  name="simple-controlled"
                  value={value2}
                  precision={0.5}
                  onChange={(event, newValue) => {
                    setValue2(newValue);
                  }}
                />
              </Box>
            </div>
          </div>
        </div>

        <div className="shadow-md shadow-[#00000040] flex flex-col items-start gap-7 pt-[1.5625rem] pr-[1.5625rem] pb-[1.5625rem] pl-[1.5625rem] w-[774px] rounded-md bg-neutral-50 mb-[30px]">
          <div className="flex items-center text-black font-['Roboto'] leading-[normal]">
            Recommanderiez-vous notre entreprise à vos collaborateurs ?
            <div className="flex items-start ml-[33px]">
              <Box
                sx={{
                  "& > legend": { mt: 2 },
                }}
              >
                <Rating
                  name="simple-controlled"
                  value={value} // Utiliser value au lieu de value2
                  precision={0.5}
                  onChange={(event, newValue) => {
                    setValue(newValue);
                  }}
                />
              </Box>
            </div>
          </div>
        </div>

        <button
          type="button"
          className="w-full inline-flex justify-center items-center gap-2.5 p-2.5 p-2 rounded bg-[#57cc99] text-[#22577a] font-medium leading-[normal]"
        >
          Envoyer
        </button>
      </div>
    </main>
  );
}
