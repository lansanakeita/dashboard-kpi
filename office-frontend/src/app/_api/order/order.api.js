import * as api from "@/app/_utils/api.js";

import axios from "axios";

export async function getOrders() {
  const request = await api.createApiRequestInstance({
    path: "/orders/get-by-customer",
    method: "GET",
  });
  const response = await axios(request);
  // console.log("data est ", response.data);
  return response.data;
}

export async function createComplaintReturn(type, description, orderId) {
  const request = await api.createApiRequestInstance({
    path: "/orders/complaint-return",
    method: "POST",
    data: { type, description, orderId },
  });
  const response = await axios(request);
  console.log("data est ", response.data);
  return response.data;
}
