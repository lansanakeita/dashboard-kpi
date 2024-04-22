import * as api from "@/app/_utils/api.js";

import axios from "axios";
import get from "lodash/get.js";

export const getDaytimeDeliveriesRate = async () => {
  const request = await api.createApiRequestInstance({
    path: "/deliveries/daytime-rate",
    method: "GET",
  });
  const response = await axios(request);

  const dayTimeDelivery = get(response, "data.dayTimeDelivery");
  
  return { dayTimeDelivery };
};

export const getDeliveriesTrends = async (start, end) => {
  const request = await api.createApiRequestInstance({
    path: `/deliveries/trends?start=${start}&end=${end}`,
    method: "GET",
  });
  const response = await axios(request);

  const deliveriesTrends = get(response, "data.deliveriesTrends");
  
  return { deliveriesTrends };
};

export const getDeliveriesRate = async () => {
  const request = await api.createApiRequestInstance({
    path: `/delivery-rates`,
    method: "GET",
  });
  const response = await axios(request);

  const deliveryRates = get(response, "data.deliveryRates");
  
  return { deliveryRates };
};