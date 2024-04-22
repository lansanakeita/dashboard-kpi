"use client";
import ListItem from "@mui/material/ListItem";
import ListItemButton from "@mui/material/ListItemButton";
import ListItemIcon from "@mui/material/ListItemIcon";
import ListItemText from "@mui/material/ListItemText";
import Link from "next/link";
import { usePathname } from "next/navigation";

export function ListItemLeftNav({ icon: Icon, text, link }) {
  const pathname = usePathname();
  return (
    <Link href={link}>
      <ListItem
        sx={{ mb: 2 }}
        style={{
          backgroundColor: pathname == link ? "#F0F0F0" : "#FFFFFF",
        }}
        disablePadding
      >
        <ListItemButton>
          <ListItemIcon>
            <Icon />
          </ListItemIcon>
          <ListItemText primary={text} />
        </ListItemButton>
      </ListItem>
    </Link>
  );
}
