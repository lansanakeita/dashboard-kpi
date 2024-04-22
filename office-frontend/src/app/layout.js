// layout.js
import { Inter } from "next/font/google";
import Head from "next/head";
import "./globals.css";
import Logo from "../assets/logo.png";
import Image from "next/image";

const inter = Inter({ subsets: ["latin"] });

export default function RootLayout({ children, logo }) {
  return (
    <html lang="en">
      <body>
        <header>
          <div className="logo p-3">
            <Image src={Logo} alt="My-App-Logo" />
          </div>
        </header>
        <main>{children}</main>
      </body>
    </html>
  );
}
