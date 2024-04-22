"use client";

import React, { useEffect, useState } from "react";
import { getOrders } from "../_api/order/order.api";
import RootLayout from "../layout";
import Logo from "@/assets/logo.png";
import PropTypes from "prop-types";
import { useTheme } from "@mui/material/styles";
import Box from "@mui/material/Box";
import Table from "@mui/material/Table";
import TableBody from "@mui/material/TableBody";
import TableCell from "@mui/material/TableCell";
import TableContainer from "@mui/material/TableContainer";
import TableFooter from "@mui/material/TableFooter";
import TablePagination from "@mui/material/TablePagination";
import TableRow from "@mui/material/TableRow";
import Paper from "@mui/material/Paper";
import IconButton from "@mui/material/IconButton";
import FirstPageIcon from "@mui/icons-material/FirstPage";
import KeyboardArrowLeft from "@mui/icons-material/KeyboardArrowLeft";
import KeyboardArrowRight from "@mui/icons-material/KeyboardArrowRight";
import LastPageIcon from "@mui/icons-material/LastPage";
import { format } from "date-fns";
import Link from "next/link";
import ArrowForwardIcon from "@mui/icons-material/ArrowForward";

function TablePaginationActions(props) {
  const theme = useTheme();
  const { count, page, rowsPerPage, onPageChange } = props;

  const handleFirstPageButtonClick = (event) => {
    onPageChange(event, 0);
  };

  const handleBackButtonClick = (event) => {
    onPageChange(event, page - 1);
  };

  const handleNextButtonClick = (event) => {
    onPageChange(event, page + 1);
  };

  const handleLastPageButtonClick = (event) => {
    onPageChange(event, Math.max(0, Math.ceil(count / rowsPerPage) - 1));
  };

  return (
    <Box sx={{ flexShrink: 0, ml: 2.5 }}>
      <IconButton
        onClick={handleFirstPageButtonClick}
        disabled={page === 0}
        aria-label="first page"
      >
        {theme.direction === "rtl" ? <LastPageIcon /> : <FirstPageIcon />}
      </IconButton>
      <IconButton
        onClick={handleBackButtonClick}
        disabled={page === 0}
        aria-label="previous page"
      >
        {theme.direction === "rtl" ? (
          <KeyboardArrowRight />
        ) : (
          <KeyboardArrowLeft />
        )}
      </IconButton>
      <IconButton
        onClick={handleNextButtonClick}
        disabled={page >= Math.ceil(count / rowsPerPage) - 1}
        aria-label="next page"
      >
        {theme.direction === "rtl" ? (
          <KeyboardArrowLeft />
        ) : (
          <KeyboardArrowRight />
        )}
      </IconButton>
      <IconButton
        onClick={handleLastPageButtonClick}
        disabled={page >= Math.ceil(count / rowsPerPage) - 1}
        aria-label="last page"
      >
        {theme.direction === "rtl" ? <FirstPageIcon /> : <LastPageIcon />}
      </IconButton>
    </Box>
  );
}

TablePaginationActions.propTypes = {
  count: PropTypes.number.isRequired,
  onPageChange: PropTypes.func.isRequired,
  page: PropTypes.number.isRequired,
  rowsPerPage: PropTypes.number.isRequired,
};

export default function Order(props) {
  const [orders, setOrders] = useState([]);
  const [page, setPage] = React.useState(0);
  const [rowsPerPage, setRowsPerPage] = React.useState(5);

  // Éviter un saut de mise en page lorsque la dernière page comporte des lignes vides.
  const emptyRows =
    page > 0 ? Math.max(0, (1 + page) * rowsPerPage - orders.length) : 0;

  const handleChangePage = (event, newPage) => {
    setPage(newPage);
  };

  const handleChangeRowsPerPage = (event) => {
    setRowsPerPage(parseInt(event.target.value, 10));
    setPage(0);
  };

  useEffect(() => {
    const fetchOrders = async () => {
      try {
        const response = await getOrders();
        console.log("🚀 ~ fetchOrders ~ response:", response);
        // Formater les dates avant de les stocker dans l'état
        const formattedOrders = response.orders.map((order) => ({
          ...order,
          orderedAt: format(
            new Date(order.orderedAt),
            "dd/MM/yyyy 'à' HH:mm:ss"
          ),
        }));
        setOrders(formattedOrders);
      } catch (error) {
        console.error(
          "Une erreur s'est produite lors de la récupération des commandes",
          error
        );
      }
    };

    fetchOrders();
  }, []);

  return (
    <div className="ml-10 mr-10">
      <h1 className="text-[#22577a] font-['Arial'] text-4xl leading-[normal] mb-[60px] text-center ">
        Mes Commandes
      </h1>

      <TableContainer component={Paper} className="text-center">
        <Table
          sx={{ width: "100%", margin: "auto" }}
          aria-label="custom pagination table"
        >
          <TableBody>
            {/* En-tête du tableau */}
            <TableRow>
              <TableCell style={{ width: "25%" }}>N° Commande</TableCell>
              <TableCell style={{ width: "25%" }}>Montant en euros</TableCell>
              <TableCell style={{ width: "25%" }}>
                Date de la commande
              </TableCell>
              <TableCell style={{ width: "25%" }}>
                Retours / Reclamations
              </TableCell>
            </TableRow>

            {(rowsPerPage > 0
              ? orders.slice(
                  page * rowsPerPage,
                  page * rowsPerPage + rowsPerPage
                )
              : orders
            ).map((order) => (
              <TableRow key={order.id}>
                <TableCell component="th" scope="row">
                  {order.orderNumber}
                </TableCell>
                <TableCell style={{ width: 100 }}>{order.amount}</TableCell>
                <TableCell style={{ width: 100 }}>{order.orderedAt}</TableCell>
                <TableCell style={{ width: 100 }}>
                  <Link href={`/order/${order.id}`}>
                    <ArrowForwardIcon
                      style={{
                        color: "#22577a",
                        width: 20,
                        height: 20,
                        borderRadius: 5,
                      }}
                    />
                  </Link>
                </TableCell>
              </TableRow>
            ))}
            {emptyRows > 0 && (
              <TableRow style={{ height: 53 * emptyRows }}>
                <TableCell colSpan={3} />
              </TableRow>
            )}
          </TableBody>
          <TableFooter>
            <TableRow>
              <TablePagination
                rowsPerPageOptions={[5, 10, 25, { label: "Tout", value: -1 }]}
                colSpan={3}
                count={orders.length}
                rowsPerPage={rowsPerPage}
                page={page}
                onPageChange={handleChangePage}
                onRowsPerPageChange={handleChangeRowsPerPage}
                ActionsComponent={TablePaginationActions}
              />
            </TableRow>
          </TableFooter>
        </Table>
      </TableContainer>
    </div>
  );
}
