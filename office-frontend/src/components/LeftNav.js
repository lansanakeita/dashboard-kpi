"use client";

import * as React from "react";

import AppBar from "@mui/material/AppBar";
import Box from "@mui/material/Box";
import CssBaseline from "@mui/material/CssBaseline";
import Divider from "@mui/material/Divider";
import Drawer from "@mui/material/Drawer";
import IconButton from "@mui/material/IconButton";
import Image from "next/image";
import List from "@mui/material/List";
import { ListItemLeftNav } from "./ListItemLeftNav";
import LocalShippingIcon from "@mui/icons-material/LocalShipping";
import LogoutIcon from "@mui/icons-material/Logout";
import LoyaltyIcon from "@mui/icons-material/Loyalty";
import MenuIcon from "@mui/icons-material/Menu";
import ReplyIcon from "@mui/icons-material/Reply";
import SignalCellularAltIcon from "@mui/icons-material/SignalCellularAlt";
import Toolbar from "@mui/material/Toolbar";
import Typography from "@mui/material/Typography";
import logo from "../assets/logo.svg";

const drawerWidth = 240;

export default function LeftNav({ children }) {
  const [mobileOpen, setMobileOpen] = React.useState(false);

  const handleDrawerToggle = () => {
    setMobileOpen(!mobileOpen);
  };

  const drawer = (
    <div>
      <Box sx={{ ml: 5, width: "100px" }}>
        <Image src={logo} alt="Logo" />
      </Box>
      <List sx={{ mt: 5 }}>
        <ListItemLeftNav
          icon={SignalCellularAltIcon}
          text="Général"
          link="/dashboard"
        />
        <ListItemLeftNav
          icon={LocalShippingIcon}
          text="Délais de livraison"
          link="/dashboard/delivery_delay"
        />
        <ListItemLeftNav
          icon={ReplyIcon}
          text="Retours et Réclamations"
          link="/"
        />
        <ListItemLeftNav icon={LoyaltyIcon} text="Fidélité" link="/" />
      </List>
      <Divider />
      <List>
        <ListItemLeftNav icon={LogoutIcon} text="Déconnexion" link="/" />
      </List>
    </div>
  );

  return (
    <Box sx={{ display: "flex" }}>
      <CssBaseline />
      <AppBar
        position="fixed"
        sx={{
          width: { sm: `calc(100% - ${drawerWidth}px)` },
          ml: { sm: `${drawerWidth}px` },
          bgcolor: "secondary.main",
        }}
      >
        <Toolbar>
          <IconButton
            color="inherit"
            aria-label="open drawer"
            edge="start"
            onClick={handleDrawerToggle}
            sx={{ mr: 2, display: { sm: "none" } }}
          >
            <MenuIcon />
          </IconButton>
          <Typography variant="h5" noWrap component="div" color="primary">
            Dashboard
          </Typography>
        </Toolbar>
      </AppBar>
      <Box
        component="nav"
        sx={{ width: { sm: drawerWidth }, flexShrink: { sm: 0 } }}
        aria-label="mailbox folders"
      >
        {/* The implementation can be swapped with js to avoid SEO duplication of links. */}
        <Drawer
          variant="temporary"
          open={mobileOpen}
          onClose={handleDrawerToggle}
          ModalProps={{
            keepMounted: true, // Better open performance on mobile.
          }}
          sx={{
            display: { xs: "block", sm: "none" },
            "& .MuiDrawer-paper": {
              boxSizing: "border-box",
              width: drawerWidth,
            },
          }}
        >
          {drawer}
        </Drawer>
        <Drawer
          variant="permanent"
          sx={{
            display: { xs: "none", sm: "block" },
            "& .MuiDrawer-paper": {
              boxSizing: "border-box",
              width: drawerWidth,
            },
          }}
          open
        >
          {drawer}
        </Drawer>
      </Box>
      <Box
        component="main"
        sx={{
          flexGrow: 1,
          p: 3,
          width: { sm: `calc(100% - ${drawerWidth}px)` },
          bgcolor: "background.main",
          color: "black",
        }}
      >
        <Toolbar />
        <Box sx={{ minHeight: "100vh" }}>{children}</Box>
      </Box>
    </Box>
  );
}
