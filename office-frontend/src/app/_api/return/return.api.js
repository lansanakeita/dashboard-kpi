import * as api from "@/app/_utils/api.js";

import axios from "axios";
import get from "lodash/get.js";

export const getReturnRate = async () => {
  const request = await api.createApiRequestInstance({
    path: "/returns/rate",
    method: "GET",
  });
  const response = await axios(request);

  const returnRate = get(response, "data.return_rate");
  
  return { returnRate };
};